<?php

namespace frontend\models;

use yii\base\Model;

class RefuseTaskForm extends Model
{
    public $refuse = true;
    public function rules(): array
    {
        return [
            [['refuse'], 'required'],
            [['refuse'], 'boolean'],
        ];
    }
}