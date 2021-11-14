<?php

use yii\db\Migration;

/**
 * Class m211106_012038_add_is_read_column
 */
class m211106_012038_add_is_read_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'message', 'is_read', $this->boolean()->defaultValue(false)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('message', 'is_read');
    }
}
