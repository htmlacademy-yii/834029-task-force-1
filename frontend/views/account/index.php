<?php

use frontend\assets\DropzoneAccountAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $user \common\models\User */
/* @var $model \frontend\models\AccountForm */
/* @var $cities array */
/* @var $categories array */

DropzoneAccountAsset::register($this);

$this->title = 'Настройки аккаунта';
$checkboxTemplate = '<label class="checkbox__legend">{input}<span>{labelTitle}</span></label>';
?>
<section class="account__redaction-wrapper">
    <h1>Редактирование настроек профиля</h1>
    <?php
    $form = ActiveForm::begin(
        [
            'options' => ['id' => 'account'],
            'enableClientValidation' => false,
            'fieldConfig' => [
                'errorOptions' => ['class' => 'registration__text-error', 'tag' => 'span'],
            ],
        ]
    ); ?>
        <div class="account__redaction-section">
            <h3 class="div-line">Настройки аккаунта</h3>
            <div class="account__redaction-section-wrapper">
                <div class="account__redaction-avatar">
                    <?= Html::img(
                        $user->avatar ?? Yii::$app->params['user_no_image'],
                        ['width' => 156, 'height' => 156]
                    ) ?>
                    <input type="file" name="avatar" id="upload-avatar">
                    <label for="upload-avatar" class="link-regular">Сменить аватар</label>
                </div>
                <div class="account__redaction">
                    <?= $form->field($model, 'name', [
                        'options' => ['class' => 'field-container account__input account__input--name']
                    ])->input('text', [
                        'class' => 'input textarea', 'placeholder' => 'Титов Денис', 'value' => $user->name
                    ])?>

                    <?= $form->field($model, 'email', [
                        'options' => ['class' => 'field-container account__input account__input--email']
                    ])->input('text', [
                        'class' => 'input textarea', 'placeholder' => 'DenisT@bk.ru', 'value' => $user->email
                    ])?>

                    <?= $form->field($model, 'address', [
                        'options' => ['class' => 'field-container account__input account__input--address']
                    ])->dropDownList($cities, ['class' => 'input textarea', 'value' => $user->city_id])?>

                    <?= $form->field($model, 'birthday', [
                        'options' => ['class' => 'field-container account__input account__input--date']
                    ])->input('date', [
                        'class' => 'input-middle input input-date',
                        'placeholder' => '15.08.1987',
                        'value' => $user->birthday,
                    ])?>

                    <?= $form->field($model, 'about', [
                        'options' => ['class' => 'field-container account__input account__input--info']
                    ])->textarea([
                        'class' => 'input textarea',
                        'rows' => 7,
                        'placeholder' => 'Place your text',
                        'value' => $user->about,
                    ])?>
                </div>
            </div>
            <h3 class="div-line">Выберите свои специализации</h3>
            <div class="account__redaction-section-wrapper">

                <div class="search-task__categories account_checkbox--bottom">
                    <?=$form->field($model, 'category', [
                        'options' => ['tag' => false],
                    ])->checkboxList($categories, [
                        'tag' => false,
                        'item' => function ($index, $label, $name, $checked, $value) use ($model) {
                            $html = Html::checkbox($name, isset($model->category[$value]), [
                                'class' => 'visually-hidden checkbox__input',
                                'value' => $value
                            ]);
                            $html .= Html::tag('span', $label);
                            $html = Html::tag('label', $html, [
                                'class' => 'checkbox__legend'
                            ]);
                            return $html;
                        }
                    ])->label(false); ?>
                </div>
            </div>
            <h3 class="div-line">Безопасность</h3>
            <div class="account__redaction-section-wrapper account__redaction">
                <?= $form->field($model, 'password', [
                    'options' => ['class' => 'field-container account__input']
                ])->input('password', ['class' => 'input textarea'])?>

                <?= $form->field($model, 'repeat_password', [
                    'options' => ['class' => 'field-container account__input']
                ])->input('password', ['class' => 'input textarea'])?>
            </div>

            <h3 class="div-line">Фото работ</h3>

            <div class="account__redaction-section-wrapper account__redaction">
                <span class="dropzone">Выбрать фотографии</span>
                <?php foreach ($user->portfolios as $portfolio) : ?>
                    <?= Html::a(
                        Html::img(
                            $portfolio->source,
                            [
                                'alt' => 'Фото работы',
                                'style' => 'object-fit: cover;',
                                'width' => 100,
                                'height' => 100,
                            ]
                        ),
                        $portfolio->source,
                        ['target' => '_blank']
                    ) ?>
                <?php endforeach; ?>
            </div>

            <h3 class="div-line">Контакты</h3>
            <div class="account__redaction-section-wrapper account__redaction">
                <?= $form->field($model, 'phone', [
                    'options' => ['class' => 'field-container account__input']
                ])->input('tel', [
                    'class' => 'input textarea', 'placeholder' => '8 (555) 187 44 87', 'value' => $user->phone
                ])?>

                <?= $form->field($model, 'skype', [
                    'options' => ['class' => 'field-container account__input']
                ])->input('text', [
                    'class' => 'input textarea', 'placeholder' => 'DenisT', 'value' => $user->skype
                ])?>

                <?= $form->field($model, 'telegram', [
                    'options' => ['class' => 'field-container account__input']
                ])->input('text', [
                    'class' => 'input textarea', 'placeholder' => '@DenisT', 'value' => $user->telegram
                ])?>
            </div>
            <h3 class="div-line">Настройки сайта</h3>
            <h4>Уведомления</h4>
            <div class="account__redaction-section-wrapper account_section--bottom">
                <div class="search-task__categories account_checkbox--bottom">
                    <?=$form->field($model, 'is_notify_about_message', [
                        'options' => ['tag' => false],
                        'checkboxTemplate' => $checkboxTemplate
                    ])->checkbox([
                        'class' => 'visually-hidden checkbox__input'
                    ])?>
                    <?=$form->field($model, 'is_notify_about_action', [
                        'options' => ['tag' => false],
                        'checkboxTemplate' => $checkboxTemplate
                    ])->checkbox([
                        'class' => 'visually-hidden checkbox__input'
                    ])?>
                    <?=$form->field($model, 'is_notify_about_review', [
                        'options' => ['tag' => false],
                        'checkboxTemplate' => $checkboxTemplate
                    ])->checkbox([
                        'class' => 'visually-hidden checkbox__input'
                    ])?>
                </div>
                <div class="search-task__categories account_checkbox account_checkbox--secrecy">
                    <?=$form->field($model, 'is_show_contacts', [
                        'options' => ['tag' => false],
                        'checkboxTemplate' => $checkboxTemplate
                    ])->checkbox([
                        'class' => 'visually-hidden checkbox__input'
                    ])?>
                    <?=$form->field($model, 'is_show_profile', [
                        'options' => ['tag' => false],
                        'checkboxTemplate' => $checkboxTemplate
                    ])->checkbox([
                        'class' => 'visually-hidden checkbox__input'
                    ])?>
                </div>
            </div>
        </div>
        <button class="button" type="submit">Сохранить изменения</button>
    <?php ActiveForm::end(); ?>
</section>