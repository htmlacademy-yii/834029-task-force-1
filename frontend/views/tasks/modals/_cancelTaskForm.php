<?php

/* @var $task \common\models\Task */

use yii\helpers\Html;

?>

<section class="modal form-modal cancel-form" id="cancel-form">
    <h2>Отмена задания</h2>
    <p>
        Вы уверены, что хотите отменить задание?
    </p>
    <?= Html::a(
        'Отменить',
        ['cancel', 'id' => $task->id],
        ['class' => 'button__form-modal refuse-button button']
    ) ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>