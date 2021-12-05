<?php

namespace frontend\controllers;

use common\models\Task;
use Yii;

class MyListController extends BaseController
{
    public function actionCompleted(): string
    {
        $user_id = Yii::$app->user->identity->getId();
        $tasks = Task::find()->where(
            [
                'AND',
                [
                    'OR',
                    ['customer_id' => $user_id],
                    ['worker_id' => $user_id]
                ],
                ['status' => \taskforce\models\Task::STATUS_COMPLETED],
            ]
        )->all();

        return $this->render('index', compact('tasks', 'user_id'));
    }

    public function actionNew(): string
    {
        $user_id = Yii::$app->user->identity->getId();
        $tasks = Task::find()->where([
            'customer_id' => $user_id,
            'status' => \taskforce\models\Task::STATUS_NEW
        ])->all();

        return $this->render('index', compact('tasks', 'user_id'));
    }

    public function actionActive(): string
    {
        $user_id = Yii::$app->user->identity->getId();
        $tasks = Task::find()->where(
            [
                'AND',
                [
                    'OR',
                    ['customer_id' => $user_id],
                    ['worker_id' => $user_id]
                ],
                ['status' => \taskforce\models\Task::STATUS_IN_WORK],
            ]
        )->all();

        return $this->render('index', compact('tasks', 'user_id'));
    }

    public function actionCanceled(): string
    {
        $user_id = Yii::$app->user->identity->getId();
        $tasks = Task::find()->where(
            [
                'AND',
                [
                    'OR',
                    ['customer_id' => $user_id],
                    ['worker_id' => $user_id],
                ],
                [
                    'OR',
                    ['status' => \taskforce\models\Task::STATUS_CANCELED],
                    ['status' => \taskforce\models\Task::STATUS_FAILED],
                ],
            ]
        )->all();

        return $this->render('index', compact('tasks', 'user_id'));
    }

    public function actionOverdue(): string
    {
        $user_id = Yii::$app->user->identity->getId();
        $tasks = Task::find()->where(
            [
                'AND',
                ['<', 'finish_at', date('Y-m-d H:i:s')],
                ['status' => \taskforce\models\Task::STATUS_IN_WORK],
            ]
        )->all();

        return $this->render('index', compact('tasks', 'user_id'));
    }
}