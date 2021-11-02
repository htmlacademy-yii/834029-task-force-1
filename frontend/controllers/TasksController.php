<?php

namespace frontend\controllers;

use common\models\Category;
use common\models\File;
use common\models\Task;
use common\models\User;
use frontend\models\CompleteTaskForm;
use frontend\models\CreateTaskForm;
use frontend\models\RefuseTaskForm;
use frontend\models\TaskFilterForm;
use taskforce\models\actions\RespondAction;
use taskforce\services\StatusService;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class TasksController extends BaseController
{
    public $enableCsrfValidation = false;
    protected StatusService $statusService;

    public function __construct($id, $module, StatusService $statusService, $config = [])
    {
        $this->statusService = $statusService;
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => false,
            'actions' => ['create'],
            'matchCallback' => function ($rule, $action) {
                return Yii::$app->user->identity->role != User::CUSTOMER_ROLE;
            }
        ];

        array_unshift($rules['access']['rules'], $rule);

        return $rules;
    }

    public function actionIndex(): string
    {
        $categories = ArrayHelper::map(Category::find()->all(), 'id', 'title');
        $filter = new TaskFilterForm();

        if (Yii::$app->request->get('category')) {
            $filter->setCategory((int)Yii::$app->request->get('category'));
        }

        if (Yii::$app->request->isPost) {
            $filter->load(Yii::$app->request->post());
        }

        $tasks = $filter->getTasks();

        return $this->render('index', [
            'tasks' => $tasks,
            'filter' => $filter,
            'categories' => $categories
        ]);
    }

    public function actionView($id): string
    {
        $user_id = Yii::$app->user->identity->getId();
        $task = Task::findOne($id);
        if (!$task) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        $is_customer = $user_id === $task->customer_id;
        $task_with_status = new \taskforce\models\Task(
            $task->customer_id,
            $task->worker_id ?? 0,
            $task->status
        );
        $actions = $task_with_status->getAvailableActions($user_id);
        $user_has_response = false;

        if ($task->isNew()) {
            foreach($task->responses as $response) {
                if ($response->worker_id === $user_id) {
                    $user_has_response = true;
                    unset($actions[(new RespondAction())->getValue()]);
                }
            }
        }

        return $this->render('view', compact(
            'actions',
            'task',
            'user_id',
            'is_customer',
            'user_has_response'
        ));
    }

    public function actionCreate(): string
    {
        $model = new CreateTaskForm();
        $categories = Category::find()->select(['title'])->indexBy('id')->column();

        if(Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            $customer_id = Yii::$app->user->identity->getId();
            $attach_id = Yii::$app->session->get('attach_id');

            if ($model->validate() && $task = $model->createTask($customer_id, $attach_id)) {
                Yii::$app->session->remove('attach_id');
                $this->redirect(['view', 'id' => $task->id]);
            }
        }

        Yii::$app->session->set('attach_id', uniqid());

        return $this->render('create', compact('model', 'categories'));
    }

    public function actionLoadFiles(): bool
    {
        if (Yii::$app->request->isAjax) {
            $files = UploadedFile::getInstancesByName('files');
            File::saveFiles($files, Yii::$app->session->get('attach_id'));

            return true;
        }

        return false;
    }

    public function actionComplete(int $task_id): \yii\web\Response
    {
        $task = Task::findOne($task_id);
        if (!$task) {
            throw new NotFoundHttpException('Задание не найдено');
        }

        if (!$task->canUserChangeStatus(Yii::$app->user->identity->getId())) {
            throw new ForbiddenHttpException('Невозможно выполнить действие');
        }

        $model = new CompleteTaskForm();
        $model->load(Yii::$app->request->post());

        if ($model->validate() && $this->statusService->completeTask($task, $model)) {
            return $this->redirect(['/tasks/view', 'id' => $task_id]);
        }

        throw new ForbiddenHttpException('Ошибка при завершении задания');
    }

    public function actionRefuse(int $id): \yii\web\Response
    {
        $task = Task::findOne($id);
        $user_id = Yii::$app->user->identity->getId();
        if (!$task) {
            throw new NotFoundHttpException('Задание не найдено');
        }

        if (!$task->inWork() || $task->worker_id !== $user_id) {
            throw new ForbiddenHttpException('Невозможно выполнить действие');
        }

        $model = new RefuseTaskForm();
        $model->load(Yii::$app->request->post());

        if ($model->validate() && $model->refuse) {
            $this->statusService->refuseTask($task);
            return $this->redirect(['/tasks/view', 'id' => $id]);
        }

        throw new ForbiddenHttpException('Ошибка при отказе от задания');
    }

    public function actionCancel(int $id): \yii\web\Response
    {
        $task = Task::findOne($id);
        $user_id = Yii::$app->user->identity->getId();
        if (!$task) {
            throw new NotFoundHttpException('Задание не найдено');
        }

        if (!$task->isNew() || $task->customer_id !== $user_id) {
            throw new ForbiddenHttpException('Невозможно выполнить действие');
        }

        $this->statusService->cancelTask($task);
        return $this->redirect(['/tasks/view', 'id' => $id]);
    }
}