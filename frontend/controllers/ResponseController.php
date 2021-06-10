<?php


namespace frontend\controllers;


use common\models\Response;
use common\models\Task;
use common\models\User;
use taskforce\services\StatusService;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class ResponseController extends BaseController
{
    protected StatusService $statusService;

    public function __construct($id, $module, StatusService $statusService, $config = [])
    {
        $this->statusService = $statusService;
        parent::__construct($id, $module, $config);
    }

    public function behaviors() : array
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => false,
            'matchCallback' => function ($rule, $action) {
                return Yii::$app->user->identity->role !== User::CUSTOMER_ROLE;
            }
        ];

        array_unshift($rules['access']['rules'], $rule);

        return $rules;
    }

    public function actionAccept($id): \yii\web\Response
    {
        $response = $this->findModel($id);

        if (!$response->canUserChangeStatus(Yii::$app->user->getId())) {
            throw new ForbiddenHttpException('Невозможно выполнить действие');
        }

        if ($this->statusService->acceptResponse($response)) {
            return $this->redirect(['/tasks/view', 'id' => $response->task_id]);
        }

        throw new ForbiddenHttpException('Невозможно выполнить действие');
    }

    public function actionRefuse($id): \yii\web\Response
    {
        $response = $this->findModel($id);

        if (!$response->canUserChangeStatus(Yii::$app->user->getId())) {
            throw new ForbiddenHttpException('Невозможно выполнить действие');
        }

        $this->statusService->refuseResponse($response);
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