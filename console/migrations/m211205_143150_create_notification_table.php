<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notification}}`.
 */
class m211205_143150_create_notification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notification}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(10)->unsigned(),
            'code' => $this->string(),
            'text' => $this->text(),
            'is_read' => $this->boolean()
        ]);

        $this->addForeignKey(
            'notification_user_id_fk',
            'notification',
            'user_id',
            'user',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%notification}}');
        $this->dropForeignKey('notification_user_id_fk', 'notification');
    }
}
