<?php
/* @var $this yii\web\View */
/* @var $tasks common\models\Task */
/* @var $filter frontend\models\TaskFilterForm */
/* @var $categories array */
/* @var $filter_data array */

use yii\helpers\Html;

$this->title = 'Новые задания';
?>

<section class="new-task">
    <div class="new-task__wrapper">
        <h1>Новые задания</h1>

        <?php foreach($tasks as $task) : ?>
            <div class="new-task__card">
                <div class="new-task__title">
                    <?= Html::a(
                        Html::tag('h2', $task->title),
                        ['/tasks/view', 'id' => $task->id],
                        ['class' => 'new-task__type link-regular']
                    ) ?>
                    <?= Html::a(
                        Html::tag('p', $task->category->title),
                        ['/tasks/index', 'category' => $task->category->id],
                        ['class' => 'link-regular']
                    ) ?>
                </div>
                <div class="new-task__icon new-task__icon--<?=$task->category->code?>"></div>
                <p class="new-task_description">
                    <?=$task->description?>
                </p>

                <?php if($task->price) : ?>
                    <b class="new-task__price new-task__price--<?=$task->category->code?>"><?=$task->price?><b> ₽</b></b>
                <?php endif; ?>

                <?php if($task->city) : ?>
                    <p class="new-task__place"><?=$task->city->name?></p>
                <?php endif; ?>

                <span class="new-task__time"><?=Yii::$app->formatter->asRelativeTime($task->created_at)?></span>
            </div>
        <?php endforeach; ?>

    </div>
    <div class="new-task__pagination">
        <ul class="new-task__pagination-list">
            <li class="pagination__item"><a href="#"></a></li>
            <li class="pagination__item pagination__item--current">
                <a>1</a></li>
            <li class="pagination__item"><a href="#">2</a></li>
            <li class="pagination__item"><a href="#">3</a></li>
            <li class="pagination__item"><a href="#"></a></li>
        </ul>
    </div>
</section>

<?= $this->render('_sidebar', [
    'filter' => $filter,
    'categories' => $categories
]) ?>