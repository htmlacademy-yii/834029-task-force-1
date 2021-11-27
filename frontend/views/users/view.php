<?php
/* @var $this yii\web\View */
/* @var $user common\models\User */

use frontend\components\RatingWidget;
use yii\helpers\Html;
use common\models\User;

$this->title = $user->name;
?>

<section class="content-view">
    <div class="user__card-wrapper">
        <div class="user__card">
            <?= Html::img(
                    $user->avatar ?? Yii::$app->params['user_no_image'],
                    ['width' => 120, 'height' => 120, 'alt' => 'Аватар пользователя']
            ) ?>
            <div class="content-view__headline">
                <h1><?= $user->name ?></h1>
                <p><?= $user->city->name . ', ' . $user->age ?></p>
                <div class="profile-mini__name five-stars__rate">
                    <?= RatingWidget::widget(['rating' => $user->workerRating]) ?>
                </div>
                <b class="done-task">
                    Выполнил <?= Yii::t(
                        'app',
                        '{n, plural, =0{0 заказов} one{# заказ} few{# заказов} many{# заказов} other{# заказов}}',
                        ['n' => count($user->workerTasks)]
                    ); ?>
                </b>
                <b class="done-review">
                    Получил <?= Yii::t(
                        'app',
                        '{n, plural, =0{0 отзывов} one{# отзыв} few{# отзыва} many{# отзывов} other{# отзывов}}',
                        ['n' => count($user->workerReviews)]
                    ); ?>
                </b>
            </div>

            <?php // TODO get current user ?>
            <div class="content-view__headline user__card-bookmark user__card-bookmark--current">
                <span><?=$user->lastActivity?></span>
                <a href="#"><b></b></a>
            </div>
        </div>
        <div class="content-view__description">
            <p><?=$user->about?></p>
        </div>
        <div class="user__card-general-information">
            <div class="user__card-info">
                <h3 class="content-view__h3">Специализации</h3>
                <div class="link-specialization">
                    <?php if(!empty($user->categories)) : ?>
                        <?php foreach($user->categories as $category) : ?>
                            <?= Html::a(
                                $category->title,
                                ['/tasks/index', 'category' => $category->id],
                                ['class' => 'link-regular']
                            ) ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <h3 class="content-view__h3">Контакты</h3>
                <div class="user__card-link">
                    <?php
                    if($user->phone) {
                        echo Html::a(
                            $user->phone,
                            'tel:'.$user->phone,
                            ['class' => 'user__card-link--tel link-regular']
                        );
                    }

                    if($user->email) {
                        echo Html::a(
                            $user->email,
                            'mailto:'.$user->email,
                            ['class' => 'user__card-link--email link-regular']
                        );
                    }

                    if($user->skype) {
                        echo Html::a(
                            $user->skype,
                            'skype:'.$user->skype.'?call',
                            ['class' => 'user__card-link--skype link-regular']
                        );
                    }
                    ?>

                </div>
            </div>

            <?php if(count($user->portfolios) > 0) : ?>
                <div class="user__card-photo">
                    <h3 class="content-view__h3">Фото работ</h3>

                    <?php foreach($user->portfolios as $photo) : ?>
                    <?= Html::a(
                        Html::img($photo->source, ['width' => 85, 'height' => 86, 'alt' => 'Фото работы']),
                        [$photo->source], // TODO get source full path
                        ['target' => '_blank']
                    ) ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if(count($user->workerReviews) > 0) : ?>
        <div class="content-view__feedback">
            <h2>Отзывы<span>(<?= count($user->workerReviews) ?>)</span></h2>
            <div class="content-view__feedback-wrapper reviews-wrapper">

                <?php foreach($user->workerReviews as $review) : ?>
                    <div class="feedback-card__reviews">
                        <p class="link-task link">
                            Задание <?= Html::a(
                                $review->task->title,
                                ['/tasks/view', 'id' => $review->task->id],
                                ['class' => 'link-regular']
                            ) ?>
                        </p>
                        <div class="card__review">
                            <?php
                            $avatar = Html::img(
                                $review->customer->avatar ?? Yii::$app->params['user_no_image'],
                                ['width' => 55, 'height' => 54]
                            );
                            echo ($review->customer->role === User::WORKER_ROLE) ?
                                Html::a(
                                    $avatar,
                                    ['/users/view', 'id' => $review->customer->id]
                                ) :
                                $avatar;
                            ?>
                            <div class="feedback-card__reviews-content">
                                <p class="link-name link">
                                    <?php
                                    echo ($review->customer->role === User::WORKER_ROLE) ?
                                    Html::a(
                                        $review->customer->name,
                                        ['/users/view', 'id' => $review->customer->id],
                                        ['class' => 'link-regular']
                                    ) :
                                    $review->customer->name;
                                    ?>
                                </p>
                                <p class="review-text">
                                    <?= $review->comment ?>
                                </p>
                            </div>
                            <div class="card__review-rate">

                                <p class="<?=$review->rating > 3 ? 'five' : 'three'?>-rate big-rate">
                                    <?= $review->rating ?><span></span>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    <?php endif; ?>
</section>
<section class="connect-desk">
    <div class="connect-desk__chat">

    </div>
</section>