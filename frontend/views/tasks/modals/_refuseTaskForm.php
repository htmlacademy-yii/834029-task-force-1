<?php

/* @var $task \common\models\Task */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$refuse_task_form = new \frontend\models\RefuseTaskForm();
?>
<section class="modal form-modal refuse-form" id="refuse-form">
    <h2>Отказ от задания</h2>
    <p>
        Вы собираетесь отказаться от выполнения задания.
        Это действие приведёт к снижению вашего рейтинга.
        Вы уверены?
    </p>
    <button class="button__form-modal button" id="close-modal"
            type="button">Отмена
    </button>

    <?php
    $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'action' => Url::to(['/tasks/refuse', 'id' => $task->id]),
        'fieldConfig' => [
            'template' => "{input}",
        ],
    ]); ?>
    <?=$form->field($refuse_task_form, 'refuse')->hiddenInput()?>
    <?= Html::button(
        'Отказаться',
        ['type' => 'submit', 'class' => 'button__form-modal refuse-button button']
    ) ?>
    <?php ActiveForm::end(); ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>