<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "response".
 *
 * @property int $id
 * @property int $task_id
 * @property int $worker_id
 * @property string $comment
 * @property int $price
 * @property string $created_at
 * @property string|null $status
 *
 * @property Task $task
 * @property User $worker
 */
class Response extends base\Response
{
    public const STATUS_NEW = 'new';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REFUSED = 'refused';

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = ['status', 'in', 'range' => [self::STATUS_NEW, self::STATUS_ACCEPTED, self::STATUS_REFUSED]];
        return $rules;
    }

    public function getWorker(): \yii\db\ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'worker_id']);
    }

    public function getTask(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }

    public function canUserChangeStatus(int $customer_id) : bool
    {
        if($this->task->customer_id === $customer_id &&
            $this->isNew() &&
            $this->task->isNew()) {
            return true;
        }

        return false;
    }

    public function isNew(): bool
    {
        return $this->status === self::STATUS_NEW;
    }
}
