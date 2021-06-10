<?php

namespace common\models;

use Yii;

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

    /**
     * Gets query for [[Worker]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorker()
    {
        return $this->hasOne(User::class, ['id' => 'worker_id']);
    }

    public function canUserChangeStatus(int $customer_id) : bool
    {
        if($this->task->customer_id === $customer_id &&
            $this->status === self::STATUS_NEW &&
            $this->task->status === \taskforce\models\Task::STATUS_NEW) {
            return true;
        }

        return false;
    }
}
