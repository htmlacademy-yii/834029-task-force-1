<?php


namespace taskforce\models\actions;


use taskforce\models\Task;

class RespondAction extends AbstractAction
{

    public function getValue(): string
    {
        return 'response';
    }

    public function getName(): string
    {
        return 'Откликнуться';
    }

    public function checkPermission(int $worker_id, int $customer_id, int $user_id): bool
    {
        return $customer_id !== $user_id;
    }
}
