<?php

namespace taskforce\models;

use taskforce\models\exceptions\NotValidStatusException;
use taskforce\models\actions\AbstractAction;
use taskforce\models\actions\ApproveAction;
use taskforce\models\actions\CancelAction;
use taskforce\models\actions\CompleteAction;
use taskforce\models\actions\RefuseAction;
use taskforce\models\actions\RespondAction;

class Task {
    private int $worker_id;
    private int $customer_id;

    public const STATUS_NEW = 'new';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_IN_WORK = 'in_work';
    public const STATUS_FAILED = 'failed';
    public const STATUS_COMPLETED = 'completed';

    private string $current_status;

    public static array $status_map = [
        self::STATUS_NEW => 'Новое',
        self::STATUS_CANCELED => 'Завершено',
        self::STATUS_IN_WORK => 'В работе',
        self::STATUS_FAILED => 'Провалено',
        self::STATUS_COMPLETED => 'Выполнено'
    ];

    public static array $status_action_map = [
        self::STATUS_NEW => [
            'respond' => null,
            'refuse' => null,
            'cancel' => self::STATUS_CANCELED,
            'approve' => self::STATUS_IN_WORK
        ],
        self::STATUS_IN_WORK => [
            'refuse' => self::STATUS_FAILED,
            'complete' => self::STATUS_COMPLETED
        ],
        self::STATUS_CANCELED => [],
        self::STATUS_FAILED => [],
        self::STATUS_COMPLETED => []
    ];

    public function __construct(int $customer_id, int $worker_id = 0, string $status = self::STATUS_NEW)
    {
        $this->worker_id = $worker_id;
        $this->customer_id = $customer_id;

        if(!self::validateStatus($status)) {
            throw new NotValidStatusException('Не корректный статус');
        }
        $this->current_status = $status;
    }

    public static function getAvailableActionsForStatus(string $status): array
    {
        switch ($status) {
            case self::STATUS_NEW:
                return [
                    new RespondAction(),
                    new CancelAction(),
                ];
            case self::STATUS_IN_WORK:
                return [
                    new RefuseAction(),
                    new CompleteAction()
                ];
            case self::STATUS_CANCELED:
            case self::STATUS_FAILED:
            case self::STATUS_COMPLETED:
                return [];
        }

        return [];
    }

    public function getAvailableActions(int $user_id): array
    {
        $actionsArray = self::getAvailableActionsForStatus($this->current_status);
        $result = [];
        if(!empty($actionsArray)) {
            foreach($actionsArray as $action) {
                /** @var AbstractAction $action */
                if($action->checkPermission($this->worker_id, $this->customer_id, $user_id)) {
                    $result[$action->getValue()] = $action;
                }
            }
        }
        return $result;
    }

    public function getNextStatus(AbstractAction $action): ?string
    {
        return self::$status_action_map[$this->current_status][$action->getValue()] ?? null;
    }

    private static function validateStatus(string $status): bool
    {
        if(isset(self::$status_map[$status])) {
            return true;
        }
        return false;
    }

    public static function getStatusMap(string $status): ?array
    {
        if(!self::validateStatus($status)) {
            throw new NotValidStatusException('Не корректный статус');
        }
        return self::$status_action_map[$status];
    }

    public function getCurrentStatus(): string
    {
        return $this->current_status;
    }

    public function getCustomerId(): int
    {
        return $this->customer_id;
    }

    public function getWorkerId(): int
    {
        return $this->worker_id;
    }

    public function setStatus(string $newStatus): bool
    {
        if(!self::validateStatus($newStatus)) {
            throw new NotValidStatusException('Не корректный статус');
        }

        $this->current_status = $newStatus;
        return true;
    }
}
