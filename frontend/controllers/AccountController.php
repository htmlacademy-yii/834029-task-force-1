<?php

namespace frontend\controllers;

use common\models\Category;
use common\models\City;
use common\models\User;
use frontend\models\AccountForm;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class AccountController extends BaseController
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $user = User::find()->where([
            'id' => Yii::$app->user->identity->getId()
        ])->with('portfolios', 'categories')
            ->one();

        $model = new AccountForm();
        $categories = Category::find()->select(['title'])->indexBy('id')->column();
        $cities = City::find()->select(['name', 'id'])->indexBy('id')->column();

        $model->category = ArrayHelper::map($user->categories, 'id', 'title');
        $model->is_show_profile = $user->is_show_profile;
        $model->is_show_contacts = $user->is_show_contacts;
        $model->is_notify_about_message = $user->is_notify_about_message;
        $model->is_notify_about_action = $user->is_notify_about_action;
        $model->is_notify_about_review = $user->is_notify_about_review;

        if(Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->avatar = UploadedFile::getInstance($model, 'avatar');

            if ($model->validate() && $user = $model->updateUser($user)) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('index', compact('user', 'model', 'categories', 'cities'));
    }
}
