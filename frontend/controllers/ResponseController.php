<?php

namespace frontend\controllers;

use common\models\Response;
use common\models\Task;
use common\models\User;
use frontend\models\AddResponseForm;
use taskforce\models\exceptions\InternalServerException;
use taskforce\services\StatusService;
use Yii;
use yii\base\Module;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class ResponseController extends BaseController
{
    private StatusService $statusService;

    public function __construct(
        string $id,
        Module $module,
        StatusService $statusService,
        array $config = []
    ) {
        $this->statusService = $statusService;
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => false,
            'actions' => ['add'],
            'matchCallback' => function ($rule, $action) {
                return Yii::$app->user->identity->role !== User::WORKER_ROLE;
            }
        ];

        array_unshift($rules['access']['rules'], $rule);
        $rules['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'add' => ['post'],
            ]
        ];

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

        throw new InternalServerException('Невозможно выполнить действие');
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

    private function findModel($id): ?Response
    {
        if (($model = Response::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Отклик не найден.');
    }

    public function actionAdd($task_id): \yii\web\Response
    {
        $user_id = Yii::$app->user->identity->getId();
        $response = Response::find()->where([
            'task_id' => $task_id,
            'worker_id' => $user_id
        ])->one();

        if ($response) {
            throw new ForbiddenHttpException('Вы уже оставляли отклик на это задание');
        }

        $task = Task::findOne($task_id);
        if (!$task) {
            throw new NotFoundHttpException('Задание не найдено');
        }
        $model = new AddResponseForm();
        $model->load(Yii::$app->request->post());

        if ($model->validate() && $model->createResponse($task_id, $user_id)) {
            return $this->redirect(['/tasks/view', 'id' => $task->id]);
        }

        throw new ForbiddenHttpException('Ошибка при создании отклика');
    }
}