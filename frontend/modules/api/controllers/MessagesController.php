<?php

namespace frontend\modules\api\controllers;

use common\models\Message;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\ServerErrorHttpException;

class MessagesController extends BaseActiveController
{
    public $modelClass = Message::class;

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

    public function actionIndex(int $task_id): array
    {
        $messages = Message::find()->where([
            'task_id' => $task_id
        ])->orderBy('created_at')->all();

        if (!empty($messages)) {
            foreach ($messages as $message) {
                /** @var $message \common\models\Message */
                if ($message->user_id !== Yii::$app->user->identity->getId()) {
                    $message->is_read = true;
                    $message->save();
                }
            }
        }

        return ArrayHelper::toArray($messages, [
            'common\models\Message' => [
                'message' => 'text',
                'published_at' => 'created_at',
                'is_mine' => function ($model) {
                    return Yii::$app->user->identity->getId() === $model->user_id;
                },
            ],
        ]);
    }

    public function actionCreate(): array
    {
        $message = new Message();
        $requestParams = Yii::$app->request->post();

        $message->task_id = $requestParams['task_id'];
        $message->text = $requestParams['message'];
        $message->user_id = Yii::$app->user->identity->getId();
        $message->created_at = date('Y-m-d H:i:s');
        $message->is_read = false;

        if ($message->save()) {
            Yii::$app->getResponse()->setStatusCode(201);
        } elseif (!$message->hasErrors()) {
            throw new ServerErrorHttpException('Не удалось создать сообщение');
        }

        return ArrayHelper::toArray($message, [
            'common\models\Message' => [
                'id',
                'message' => 'text',
                'published_at' => 'created_at',
                'is_mine' => true,
            ],
        ]);
    }
}