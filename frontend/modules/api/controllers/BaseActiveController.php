<?php

namespace frontend\modules\api\controllers;

use yii\filters\AccessControl;
use yii\rest\ActiveController;

class BaseActiveController extends ActiveController
{
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
}