<?php
/* @var $this yii\web\View */
/* @var $user_id int */
/* @var $is_customer bool */
/* @var $user_has_response bool */
/* @var $task \common\models\Task */
/* @var $actions \taskforce\models\actions\AbstractAction[] */

use frontend\components\RatingWidget;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<section class="content-view">
    <div class="content-view__card">
        <div class="content-view__card-wrapper">
            <div class="content-view__header">
                <div class="content-view__headline">
                    <h1><?=$task->title?></h1>
                    <span>Размещено в категории
                        <?=Html::a(
                            $task->category->title,
                            ['/tasks/index', 'category' => $task->category->id],
                            ['class' => 'link-regular']
                        )?>
                        <?=Yii::$app->formatter->asRelativeTime($task->created_at)?>
                    </span>
                </div>
                <?php if ($task->price) : ?>
                    <b class="new-task__price new-task__price--<?=$task->category->code?> content-view-price">
                        <?=$task->price?><b> ₽</b>
                    </b>
                <?php endif; ?>
                <div class="new-task__icon new-task__icon--<?=$task->category->code?> content-view-icon"></div>
            </div>
            <div class="content-view__description">
                <h3 class="content-view__h3">Общее описание</h3>
                <p>
                    <?=$task->description?>
                </p>
            </div>

            <?php if (count($task->files) > 0) : ?>
                <div class="content-view__attach">
                    <h3 class="content-view__h3">Вложения</h3>
                    <?php foreach($task->files as $file) : ?>
                        <?=Html::a($file->name, $file->source)?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="content-view__location">
                <h3 class="content-view__h3">Расположение</h3>
                <div class="content-view__location-wrapper">
                    <div class="content-view__map">
                        <a href="#"><img src="/img/map.jpg" width="361" height="292"
                                         alt="Москва, Новый арбат, 23 к. 1"></a>
                    </div>
                    <div class="content-view__address">
                        <span class="address__town">Москва</span><br>
                        <span>Новый арбат, 23 к. 1</span>
                        <p>Вход под арку, код домофона 1122</p>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($actions)) : ?>
            <div class="content-view__action-buttons">
                <?php foreach($actions as $action) : ?>
                    <button class="button button__big-color <?=$action->getValue()?>-button open-modal"
                            type="button" data-for="<?=$action->getValue()?>-form"><?=$action->getName()?>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if (count($task->responses) > 0 && ($is_customer || $user_has_response)) : ?>
        <div class="content-view__feedback">
            <?php if ($is_customer) : ?>
                <h2>
                    Отклики <span>(<?=count($task->responses)?>)</span>
                </h2>
            <?php elseif ($user_has_response) : ?>
                <h2>
                    Ваш отклик
                </h2>
            <?php endif; ?>

            <div class="content-view__feedback-wrapper">

                <?php foreach($task->responses as $response) : ?>
                    <?php if ($is_customer || $user_id === $response->worker_id) : ?>
                        <div class="content-view__feedback-card">
                            <div class="feedback-card__top">
                                <?= Html::a(
                                    Html::img(
                                        $response->worker->avatar ?? Yii::$app->params['user_no_image'],
                                        ['width' => 55, 'height' => 55]
                                    ),
                                    ['/users/view', 'id' => $response->worker->id]
                                ) ?>
                                <div class="feedback-card__top--name">
                                    <p>
                                        <?=Html::a(
                                            $response->worker->name,
                                            ['/users/view', 'id' => $response->worker->id],
                                            ['class' => 'link-regular']
                                        )?>
                                    </p>
                                    <?= RatingWidget::widget(['rating' => $response->worker->workerRating]) ?>
                                </div>
                                <span class="new-task__time">
                                    <?=Yii::$app->formatter->asRelativeTime($response->created_at)?>
                                </span>
                            </div>
                            <div class="feedback-card__content">
                                <p>
                                    <?=$response->comment?>
                                </p>
                                <span><?=$response->price?> ₽</span>
                            </div>

                            <?php if ($is_customer &&
                                    $task->isNew() &&
                                    $response->isNew()) : ?>
                                <div class="feedback-card__actions">
                                    <?= Html::a(
                                        'Подтвердить',
                                        ['/response/accept', 'id' => $response->id],
                                        ['class' => 'button__small-color response-button button', 'type' => 'button']
                                    ) ?>
                                    <?= Html::a(
                                        'Отказать',
                                        ['/response/refuse', 'id' => $response->id],
                                        ['class' => 'button__small-color refuse-button button', 'type' => 'button']
                                    ) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>

            </div>
        </div>
    <?php endif; ?>

</section>
<section class="connect-desk">
    <div class="connect-desk__profile-mini">
        <div class="profile-mini__wrapper">
            <h3>Заказчик</h3>
            <div class="profile-mini__top">
                <?php
                $avatar = Html::img(
                    $task->customer->avatar ?? Yii::$app->params['user_no_image'],
                    ['width' => 62, 'height' => 62]
                );
                echo ($task->customer->isWorker()) ?
                    Html::a(
                        $avatar,
                        ['/users/view', 'id' => $task->customer->id]
                    ) :
                    $avatar;
                ?>
                <div class="profile-mini__name five-stars__rate">
                    <p><?=$task->customer->name?></p>
                </div>
            </div>
            <p class="info-customer">
                <span>
                    <?= Yii::t(
                        'app',
                        '{n, plural, =0{0 заданий} one{# задание} few{# задания} many{# заданий} other{# заданий}}',
                        ['n' => count($task->customer->customerTasks)]
                    ); ?>
                </span>
                <span class="last-">
                    <?=$task->customer->registerDuration?>
                </span>
            </p>

            <?php if ($task->customer->isWorker()) : ?>
                <?= Html::a(
                    'Смотреть профиль',
                    ['/users/view', 'id' => $task->customer->id],
                    ['class' => 'link-regular']
                ) ?>
            <?php endif; ?>
        </div>
    </div>
    <div id="chat-container">
        <!--                    добавьте сюда атрибут task с указанием в нем id текущего задания-->
        <chat class="connect-desk__chat"></chat>
    </div>
</section>

<?php if (!$is_customer && $task->isNew()) : ?>
    <?php $add_response_form = new \frontend\models\AddResponseForm(); ?>
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
<?php endif; ?>

<?php if ($is_customer && $task->inWork()) : ?>
    <section class="modal completion-form form-modal" id="complete-form">
        <h2>Завершение задания</h2>
        <p class="form-modal-description">Задание выполнено?</p>
        <?php $complete_task_form = new \frontend\models\CompleteTaskForm(); ?>
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
<?php endif; ?>

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
    <button class="button__form-modal refuse-button button"
            type="button">Отказаться
    </button>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>
<section class="modal form-modal cancel-form" id="cancel-form">
    <h2>Отмена задания</h2>
    <p>
        Вы уверены, что хотите отменить задание?
    </p>
    <button class="button__form-modal refuse-button button" type="button">
        Отменить
    </button>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>

<div class="overlay"></div>