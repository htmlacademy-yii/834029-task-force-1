<?php

use yii\bootstrap\ActiveForm;

/* @var $model \frontend\models\SignupForm */
/* @var $cities array */

$this->title = 'Регистрация аккаунта';
?>

<section class="registration__user">
    <h1>Регистрация аккаунта</h1>
    <div class="registration-wrapper">
        <?php

        $form = ActiveForm::begin(
            [
                'options' => [
                    'class' => 'registration__user-form form-create',
                ],
                'fieldConfig' => [
                    'options' => [
                        'class' => 'field-container field-container--registration'
                    ],
                    'labelOptions' => ['class' => ''],
                    'errorOptions' => ['class' => 'registration__text-error', 'tag' => 'span']
                ],
            ]
        ); ?>

        <?= $form->field($model, 'email')
                 ->input(
                     'email',
                     [
                         'class' => 'input textarea',
                         'placeholder' => 'kumarm@mail.ru'
                     ]
                 )
        ?>

        <?= $form->field($model, 'name')
                 ->textInput(
                     [
                         'class' => 'input textarea',
                         'placeholder' => 'Мамедов Кумар'
                     ]
                 )
        ?>

        <?= $form->field($model, 'city_id')->dropDownList($cities, [
            'class' => 'multiple-select input town-select registration-town',
        ]) ?>

        <?= $form->field($model, 'password')->passwordInput([
            'class' => 'input textarea',
        ]) ?>

        <?= \yii\helpers\Html::button('Создать аккаунт', ['type' => 'submit', 'class' => 'button button__registration']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</section>