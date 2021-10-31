<?php

/* @var $task \common\models\Task */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$complete_task_form = new \frontend\models\CompleteTaskForm();
?>

<section class="modal completion-form form-modal" id="complete-form">
    <h2>Завершение задания</h2>
    <p class="form-modal-description">Задание выполнено?</p>

    <?php
    $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'action' => Url::to(['/tasks/complete', 'task_id' => $task->id]),
        'fieldConfig' => [
            'template' => "{label}\n{input}\n",
            'options' => [
                'tag' => false,
            ],
            'labelOptions' => ['class' => 'form-modal-description'],
            'errorOptions' => ['class' => 'registration__text-error', 'tag' => 'span']
        ],
    ]); ?>

    <?=
    $form->field($complete_task_form, 'isComplete')
        ->radioList(
            [$complete_task_form::COMPLETE => 'Да', $complete_task_form::DIFFICULT => 'Возникли проблемы'],
            [
                'tag' => false,
                'item' => function($index, $label, $name, $checked, $value) {

                    $return = '<input id="completion-radio--' . $value .'" type="radio" ';
                    $return .= 'class="visually-hidden completion-input completion-input--' . $value . '" ';
                    $return .= 'name="' . $name . '" value="' . $value . '">';
                    $return .= '<label class="completion-label completion-label--' . $value . '" ';
                    $return .= 'for="completion-radio--' . $value . '">' . $label . '</label>';

                    return $return;
                }
            ]
        )
        ->label(false);
    ?>
    <p>
        <?= $form->field($complete_task_form, 'comment')
            ->textarea(['class' => 'input textarea', 'placeholder' => 'Текст комментария', 'rows' => 4])?>
    </p>
    <p class="form-modal-description">
        Оценка
    <div class="feedback-card__top--name completion-form-star">
        <span class="star-disabled"></span>
        <span class="star-disabled"></span>
        <span class="star-disabled"></span>
        <span class="star-disabled"></span>
        <span class="star-disabled"></span>
    </div>
    </p>
    <?= $form->field($complete_task_form, 'rating')->hiddenInput(['id'=> 'rating'])->label(false);?>
    <?= Html::button('Отправить', ['type' => 'submit', 'class' => 'button modal-button']) ?>
    <?php ActiveForm::end(); ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>