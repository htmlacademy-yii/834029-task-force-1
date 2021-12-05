<?php
/* @var $this yii\web\View */
/* @var $users common\models\User */
/* @var $filter frontend\models\UserFilterForm */
/* @var $categories array */

use frontend\components\RatingWidget;
use yii\helpers\Html;
use frontend\models\UserFilterForm;
use common\models\User;

$sort = Yii::$app->request->get('sort') ?? '';
$active_link_class = 'user__search-item--current';

$this->title = 'Список пользователей';
?>

<section class="user__search">

    <div class="user__search-link">
        <p>Сортировать по:</p>
        <ul class="user__search-list">
            <li class="user__search-item <?=($sort === UserFilterForm::SORT_RATING) ? $active_link_class : ''?>">
                <?=Html::a('Рейтингу', ['index', 'sort' => UserFilterForm::SORT_RATING], ['class' => 'link-regular'])?>
            </li>
            <li class="user__search-item <?=($sort === UserFilterForm::SORT_COUNT) ? $active_link_class : ''?>">
                <?=Html::a('Числу заказов', ['index', 'sort' => UserFilterForm::SORT_COUNT], ['class' => 'link-regular'])?>
            </li>
            <li class="user__search-item <?=($sort === UserFilterForm::SORT_POPULARITY) ? $active_link_class : ''?>">
                <?=Html::a('Популярности', ['index', 'sort' => UserFilterForm::SORT_POPULARITY], ['class' => 'link-regular'])?>
            </li>
        </ul>
    </div>

    <?php foreach($users as $user) : ?>
        <div class="content-view__feedback-card user__search-wrapper">
            <div class="feedback-card__top">
                <div class="user__search-icon">
                    <?= Html::a(
                            Html::img($user->avatar ?? Yii::$app->params['user_no_image'], ['width' => 65, 'height' => 65]),
                            ['view', 'id' => $user->id]
                    ) ?>
                    <span>
                        <?= Yii::t(
                            'app',
                            '{n, plural, =0{0 заданий} one{# задание} few{# задания} many{# заданий} other{# заданий}}',
                            ['n' => count($user->workerTasks)]
                        ); ?>
                    </span>
                    <span>
                        <?= Yii::t(
                            'app',
                            '{n, plural, =0{0 отзывов} one{# отзыв} few{# отзыва} many{# отзывов} other{# отзывов}}',
                            ['n' => count($user->workerReviews)]
                        ); ?>
                    </span>
                </div>
                <div class="feedback-card__top--name user__search-card">
                    <p class="link-name">
                        <?= Html::a($user->name, ['view', 'id' => $user->id], ['class' => 'link-regular']) ?>
                    </p>

                    <?= RatingWidget::widget(['rating' => $user->workerRating]) ?>

                    <?php if($user->about) : ?>
                        <p class="user__search-content">
                            <?=$user->about?>
                        </p>
                    <?php endif; ?>
                </div>
                <span class="new-task__time">
                    <?=$user->lastActivity?>
                </span>
            </div>
            <div class="link-specialization user__search-link--bottom">
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
        </div>
    <?php endforeach; ?>
</section>

<?= $this->render('_sidebar', [
    'filter' => $filter,
    'categories' => $categories
]) ?>
