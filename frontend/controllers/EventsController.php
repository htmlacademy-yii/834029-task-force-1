<?php

namespace frontend\controllers;

use common\models\Notification;
use Yii;

class EventsController extends BaseController
{
    public function actionIndex()
    {
        if (Yii::$app->request->isAjax) {
            $events = Notification::find()->where([
                'user_id' => Yii::$app->user->identity->getId(),
                'is_read' => false
            ])->all();

            foreach ($events as $event) {
                $event->is_read = true;
                $event->save();
            }
        }
    }
}