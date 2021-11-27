<?php

namespace frontend\controllers;

class AccountController extends BaseController
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }
}
