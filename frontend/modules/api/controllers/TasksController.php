<?php

namespace frontend\modules\api\controllers;

use common\models\Task;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class TasksController extends BaseActiveController
{
    public $modelClass = Task::class;

    public function actions() {
        $actions = parent::actions();
        unset(
            $actions['index'],
            $actions['view'],
            $actions['create'],
            $actions['update'],
            $actions['delete']
        );
        return $actions;
    }

    public function actionIndex()
    {
        $tasks = Task::find()->where([
            'worker_id' => Yii::$app->user->identity->getId()
        ])->all();

        return ArrayHelper::toArray($tasks, [
            'common\models\Task' => [
                'title',
                'published_at' => 'created_at',
                'new_messages' => function ($task) {
                    $count = 0;
                    foreach ($task->messages as $message) {
                        if ($message->is_read === 0 && $message->user_id !== Yii::$app->user->identity->getId()) {
                            $count++;
                        }
                    }
                    return $count;
                },
                'author_name' => 'customer.name',
                'id'
            ],
        ]);
    }
}