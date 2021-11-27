<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $login_form \frontend\models\SigninForm */
/* @var $tasks array */

$this->title = 'Taskforce';
?>
<main>
    <div class="landing-container">
        <div class="landing-top">
            <h1>Работа для всех.<br>
                Найди исполнителя на любую задачу.</h1>
            <p>Сломался кран на кухне? Надо отправить документы? Нет времени самому гулять с собакой?
                У нас вы быстро найдёте исполнителя для любой жизненной ситуации?<br>
                Быстро, безопасно и с гарантией. Просто, как раз, два, три. </p>
            <?=Html::a('Создать аккаунт', ['/register'], ['class' => 'button'])?>
        </div>
        <div class="landing-center">
            <div class="landing-instruction">
                <div class="landing-instruction-step">
                    <div class="instruction-circle circle-request"></div>
                    <div class="instruction-description">
                        <h3>Публикация заявки</h3>
                        <p>Создайте новую заявку.</p>
                        <p>Опишите в ней все детали
                            и  стоимость работы.</p>
                    </div>
                </div>
                <div class="landing-instruction-step">
                    <div class="instruction-circle  circle-choice"></div>
                    <div class="instruction-description">
                        <h3>Выбор исполнителя</h3>
                        <p>Получайте отклики от мастеров.</p>
                        <p>Выберите подходящего<br>
                            вам исполнителя.</p>
                    </div>
                </div>
                <div class="landing-instruction-step">
                    <div class="instruction-circle  circle-discussion"></div>
                    <div class="instruction-description">
                        <h3>Обсуждение деталей</h3>
                        <p>Обсудите все детали работы<br>
                            в нашем внутреннем чате.</p>
                    </div>
                </div>
                <div class="landing-instruction-step">
                    <div class="instruction-circle circle-payment"></div>
                    <div class="instruction-description">
                        <h3>Оплата&nbsp;работы</h3>
                        <p>По завершении работы оплатите
                            услугу и закройте задание</p>
                    </div>
                </div>
            </div>
            <div class="landing-notice">
                <div class="landing-notice-card card-executor">
                    <h3>Исполнителям</h3>
                    <ul class="notice-card-list">
                        <li>
                            Большой выбор заданий
                        </li>
                        <li>
                            Работайте где  удобно
                        </li>
                        <li>
                            Свободный график
                        </li>
                        <li>
                            Удалённая работа
                        </li>
                        <li>
                            Гарантия оплаты
                        </li>
                    </ul>
                </div>
                <div class="landing-notice-card card-customer">
                    <h3>Заказчикам</h3>
                    <ul class="notice-card-list">
                        <li>
                            Исполнители на любую задачу
                        </li>
                        <li>
                            Достоверные отзывы
                        </li>
                        <li>
                            Оплата по факту работы
                        </li>
                        <li>
                            Экономия времени и денег
                        </li>
                        <li>
                            Выгодные цены
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="landing-bottom">
            <div class="landing-bottom-container">
                <h2>Последние задания на сайте</h2>

                <?php foreach($tasks as $task) : ?>
                    <div class="landing-task">
                    <div class="landing-task-top task-<?=$task->category->code?>"></div>
                    <div class="landing-task-description">
                        <h3>
                            <?=Html::a($task->title, ['/tasks/view', 'id' => $task->id], ['class' => 'link-regular'])?>
                        </h3>
                        <p>
                            <?=$task->shortDescription?>
                        </p>
                    </div>
                    <div class="landing-task-info">
                        <div class="task-info-left">
                            <p>
                                <?= Html::a(
                                    $task->category->title,
                                    ['/tasks/index', 'category' => $task->category->id],
                                    ['class' => 'link-regular']
                                ) ?>
                            </p>
                            <p><?=Yii::$app->formatter->asRelativeTime($task->created_at)?></p>
                        </div>
                        <?php if($task->price) : ?>
                            <span><?=$task->price?> <b>₽</b></span>
                        <?php endif; ?>

                    </div>
                </div>
                <?php endforeach; ?>

            </div>
            <div class="landing-bottom-container">
                <button type="button" class="button red-button open-modal" data-for="enter-form">
                    смотреть все задания
                </button>
            </div>
        </div>
    </div>
</main>

<section class="modal enter-form form-modal" id="enter-form" <?=Yii::$app->request->isPost ? 'style="display:block;"' : ''?>>
    <h2>Вход на сайт</h2>

    <?php
    $form = ActiveForm::begin(
        [
            'fieldConfig' => [
                'options' => [
                    'tag' => 'p'
                ],
                'labelOptions' => ['class' => 'form-modal-description'],
                'errorOptions' => ['class' => 'registration__text-error', 'tag' => 'span']
            ],
        ]
    ); ?>

    <?= $form->field($login_form, 'email')
             ->input('text', ['class' => 'enter-form-email input input-middle'])?>

    <?= $form->field($login_form, 'password')
             ->input('password', ['class' => 'enter-form-email input input-middle'])?>


    <?= Html::button('Войти', ['type' => 'submit', 'class' => 'button']) ?>

    <?php ActiveForm::end(); ?>

    <button class="form-modal-close" type="button">Закрыть</button>
</section>