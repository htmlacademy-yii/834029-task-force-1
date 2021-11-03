<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use frontend\assets\DropzoneAsset;

/* @var $model \frontend\models\CreateTaskForm */
/* @var $categories array */

DropzoneAsset::register($this);
?>

<section class="create__task">
    <h1>Публикация нового задания</h1>
    <div class="create__task-main">
        <?php
        $form = ActiveForm::begin(
            [
                'options' => ['class' => 'create__task-form form-create', 'id' => 'task-form'],
                'enableClientValidation' => false,
                'fieldConfig' => [
                    'options' => [
                        'class' => 'field-container'
                    ],
                    'labelOptions' => ['class' => ''],
                    'errorOptions' => ['class' => 'registration__text-error', 'tag' => 'span'],
                    'hintOptions' => ['class' => '', 'tag' => 'span']
                ],
            ]
        ); ?>

        <?= $form->field($model, 'title')
                 ->input('text', ['class' => 'input textarea', 'placeholder' => 'Повесить полку'])
                 ->hint('Кратко опишите суть работы')?>

        <?= $form->field($model, 'description')
            ->textarea(['class' => 'input textarea', 'rows' => 7, 'placeholder' => 'Ваше описание'])
            ->hint('Укажите все пожелания и детали, чтобы исполнителям было проще соориентироваться')?>

        <?= $form->field($model, 'category_id')
            ->dropDownList($categories, ['class' => 'multiple-select input multiple-select-big', 'size' => 1])
            ->hint('Выберите категорию')?>

        <div class="field-container">
            <label>Файлы</label>
            <span>Загрузите файлы, которые помогут исполнителю лучше выполнить или оценить работу</span>
            <div class="create__file">
                <span>Добавить новый файл</span>
            </div>
        </div>

        <?= $form->field($model, 'location')
            ->input('search', [
                'class' => 'input-navigation input-middle input',
                'placeholder' => 'Санкт-Петербург, Калининский район'
            ])
            ->hint('Укажите адрес исполнения, если задание требует присутствия')?>

        <div class="create__price-time">
            <?= $form->field($model, 'price', ['options' => ['class' => 'field-container create__price-time--wrapper']])
                ->input('text', ['class' => 'input textarea input-money', 'placeholder' => '1000'])
                ->hint('Не заполняйте для оценки исполнителем')?>

            <?= $form->field($model, 'finish_at', ['options' => ['class' => 'field-container create__price-time--wrapper']])
                ->input('date', ['class' => 'input-middle input input-date', 'placeholder' => '10.11, 15:00'])
                ->hint('Укажите крайний срок исполнения')?>
        </div>

        <?php ActiveForm::end(); ?>

        <div class="create__warnings">
            <div class="warning-item warning-item--advice">
                <h2>Правила хорошего описания</h2>
                <h3>Подробности</h3>
                <p>Друзья, не используйте случайный<br>
                    контент – ни наш, ни чей-либо еще. Заполняйте свои
                    макеты, вайрфреймы, мокапы и прототипы реальным
                    содержимым.</p>
                <h3>Файлы</h3>
                <p>Если загружаете фотографии объекта, то убедитесь,
                    что всё в фокусе, а фото показывает объект со всех
                    ракурсов.</p>
            </div>

            <?php if($model->hasErrors()) : ?>
                <div class="warning-item warning-item--error">
                    <h2>Ошибки заполнения формы</h2>

                    <?php foreach($model->getErrors() as $key => $errors) : ?>

                        <h3><?=$model->attributeLabels()[$key]?></h3>
                        <p>
                            <?php foreach ($errors as $error): ?>
                                <?=$error?>
                            <?php endforeach; ?>
                        </p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?= Html::button('Опубликовать', ['type' => 'submit', 'class' => 'button', 'form' => 'task-form']) ?>

</section>

<script src="/js/dropzone.js"></script>
<script>

    var dropzone = new Dropzone(".create__file", {
        url: "/tasks/load-files",
        uploadMultiple: true,
        paramName: "files",
    });
</script>