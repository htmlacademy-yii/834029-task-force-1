<?php


namespace taskforce\models\actions;


use taskforce\models\Task;

class CancelAction extends AbstractAction
{

    public function getValue() :string
    {
        return 'cancel';
    }

    public function getName() :string
    {
        return 'Отменить';
    }

    public function checkPermission(int $worker_id, int $customer_id, int $user_id): bool
    {
        return $customer_id === $user_id;
    }
}
