<?php

/* @var $task \common\models\Task */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$add_response_form = new \frontend\models\AddResponseForm();
?>

<section class="modal response-form form-modal" id="response-form">
    <h2>Отклик на задание</h2>
    <?php
    $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'action' => Url::to(['/response/add', 'task_id' => $task->id]),
        'fieldConfig' => [
            'options' => [
                'tag' => 'p'
            ],
            'labelOptions' => ['class' => 'form-modal-description'],
            'errorOptions' => ['class' => 'registration__text-error', 'tag' => 'span']
        ],
    ]); ?>

    <?= $form->field($add_response_form, 'price', ['options' => ['class' => 'field-container create__price-time--wrapper']])
        ->input('text', ['class' => 'response-form-payment input input-middle input-money'])?>

    <?= $form->field($add_response_form, 'comment')
        ->textarea(['class' => 'input textarea', 'placeholder' => 'Текст комментария', 'rows' => 4])?>

    <?= Html::button('Отправить', ['type' => 'submit', 'class' => 'button modal-button']) ?>
    <?php ActiveForm::end(); ?>

    <button class="form-modal-close" type="button">Закрыть</button>
</section>