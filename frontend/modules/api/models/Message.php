<?php

namespace frontend\modules\api\models;

class Message extends \common\models\Message
{
    const SCENARIO_CREATE = 'create';

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['id', 'task_id', 'user_id', 'text', 'created_at'];
        return $scenarios;
    }

    public function fields(): array
    {
        $fields = [
            'message' => 'text',
            'published_at' => 'created_at',
            'is_mine' => function ($model) {
                return \Yii::$app->user->identity->getId() === $model->user_id;
            },
        ];

        if ($this->scenario === self::SCENARIO_CREATE) {
            array_unshift($fields, 'id');
        }

        return $fields;
    }
}