<?php


namespace taskforce\models\actions;


use taskforce\models\Task;

class ApproveAction extends AbstractAction
{
    public static function getValue() :string
    {
        return 'approve';
    }

    public static function getName() :string
    {
        return 'Утвердить';
    }

    public function checkPermission(int $worker_id, int $customer_id, int $user_id) :bool
    {
        return $customer_id === $user_id;
    }
}
