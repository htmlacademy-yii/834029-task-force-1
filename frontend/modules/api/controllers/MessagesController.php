<?php

namespace frontend\modules\api\controllers;

use frontend\modules\api\models\Message;
use Yii;
use yii\filters\AccessControl;
use yii\rest\ActiveController;
use yii\web\ServerErrorHttpException;

class MessagesController extends ActiveController
{
    public $modelClass = Message::class;

    public function behaviors(): array
    {
        $rules = parent::behaviors();
        $rules['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@']
                ]
            ]
        ];
        return $rules;
    }

    public function actions() {
        $actions = parent::actions();
        unset($actions['view'], $actions['create'], $actions['update'], $actions['delete']);
        return $actions;
    }

    public function actionCreate(): Message
    {
        $model = new Message();
        $model->scenario = $model::SCENARIO_CREATE;
        $requestParams = Yii::$app->request->post();

        $model->task_id = $requestParams['task_id'];
        $model->text = $requestParams['message'];
        $model->user_id = Yii::$app->user->identity->getId();
        $model->created_at = date('Y-m-d H:i:s');

        if ($model->save()) {
            Yii::$app->getResponse()->setStatusCode(201);
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $model;
    }
}