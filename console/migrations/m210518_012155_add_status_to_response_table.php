<?php

use yii\db\Migration;
use common\models\Response;

/**
 * Class m210518_012155_add_status_to_response_table
 */
class m210518_012155_add_status_to_response_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'response',
            'status',
            "ENUM('" . Response::STATUS_NEW . "', '" . Response::STATUS_ACCEPTED . "', '" . Response::STATUS_REFUSED . "') DEFAULT '"  . Response::STATUS_NEW . "'");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('response', 'status');
    }
}
