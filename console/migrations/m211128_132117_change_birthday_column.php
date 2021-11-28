<?php

use yii\db\Migration;

/**
 * Class m211128_132117_change_birthday_column
 */
class m211128_132117_change_birthday_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('user', 'birthday', self::date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('user', 'birthday', self::dateTime());
    }
}
