<?php

namespace frontend\controllers;

use common\models\Category;
use common\models\File;
use common\models\Task;
use common\models\User;
use frontend\models\CreateTaskForm;
use frontend\models\TaskFilterForm;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class TasksController extends BaseController
{
    public $enableCsrfValidation = false;

    public function behaviors() : array
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

        $task_with_status = new \taskforce\models\Task(
            $task->customer_id,
            $task->worker_id ?? 0,
            $task->status
        );
        $actions = $task_with_status->getAvailableActions($user_id);

        return $this->render('view', compact('actions', 'task', 'user_id'));
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

    public function actionLoadFiles() : bool
    {
        if (Yii::$app->request->isAjax) {
            $files = UploadedFile::getInstancesByName('files');
            File::saveFiles($files, Yii::$app->session->get('attach_id'));

            return true;
        }

        return false;
    }
}