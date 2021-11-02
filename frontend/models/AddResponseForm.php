<?php

namespace frontend\models;

use common\models\Response;
use yii\base\Model;

class AddResponseForm extends Model
{
    public $price;
    public $comment;

    public function attributeLabels(): array
    {
        return [
            'price' => 'Ваша цена',
            'comment' => 'Комментарий',
        ];
    }

    public function rules(): array
    {
        return [
            [['comment'], 'string'],
            [['price'], 'integer'],
            [['price'], 'compare', 'compareValue' => 0, 'operator' => '>'],
        ];
    }

    public function createResponse(int $task_id, int $worker_id): ?Response
    {
        $response = new Response();
        $response->task_id = $task_id;
        $response->worker_id = $worker_id;
        $response->comment = $this->comment;
        $response->price = $this->price;
        $response->status = Response::STATUS_NEW;
        if ($response->validate() && $response->save()) {
            // TODO отправить уведомление
            return $response;
        }

        $this->addErrors($response->getErrors());

        return null;
    }
}