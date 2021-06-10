<?php


namespace frontend\controllers;


use common\models\Response;
use common\models\Task;
use common\models\User;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class ResponseController extends BaseController
{
    public function behaviors() : array
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => false,
            'matchCallback' => function ($rule, $action) {
                return Yii::$app->user->identity->role != User::CUSTOMER_ROLE;
            }
        ];

        array_unshift($rules['access']['rules'], $rule);

        return $rules;
    }

    public function actionAccept($id)
    {
        $response = $this->findModel($id);

        if(!$response->canUserChangeStatus(Yii::$app->user->getId())) {
            throw new ForbiddenHttpException('Невозможно выполнить действие');
        }

        $response->status = Response::STATUS_ACCEPTED;
        $response->save();

        $task = Task::findOne($response->task_id);
        $task->status = \taskforce\models\Task::STATUS_IN_WORK;
        $task->worker_id = $response->worker_id;
        $task->save();

        return $this->redirect(['/tasks/view', 'id' => $response->task_id]);
    }

    public function actionRefuse($id)
    {
        $response = $this->findModel($id);

        if(!$response->canUserChangeStatus(Yii::$app->user->getId())) {
            throw new ForbiddenHttpException('Невозможно выполнить действие');
        }

        $response->status = Response::STATUS_REFUSED;
        $response->save();

        return $this->redirect(['/tasks/view', 'id' => $response->task_id]);
    }

    private function findModel($id) : ?Response
    {
        if (($model = Response::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Отклик не найден.');

    }
}