<?php


namespace taskforce\models\actions;


use taskforce\models\Task;

class CancelAction extends AbstractAction
{

    public static function getValue() :string
    {
        return 'cancel';
    }

    public static function getName() :string
    {
        return 'Завершить';
    }

    public function checkPermission(int $worker_id, int $customer_id, int $user_id): bool
    {
        return $customer_id === $user_id;
    }
}
