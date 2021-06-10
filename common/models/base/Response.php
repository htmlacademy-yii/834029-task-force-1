<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "response".
 *
 * @property int $id
 * @property int $task_id
 * @property int $worker_id
 * @property string $comment
 * @property int $price
 * @property string $created_at
 * @property string|null $status
 *
 * @property Task $task
 * @property User $worker
 */
class Response extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'response';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'worker_id', 'comment', 'price'], 'required'],
            [['task_id', 'worker_id', 'price'], 'integer'],
            [['comment', 'status'], 'string'],
            [['created_at'], 'safe'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
            [['worker_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['worker_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'worker_id' => 'Worker ID',
            'comment' => 'Comment',
            'price' => 'Price',
            'created_at' => 'Created At',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }

    /**
     * Gets query for [[Worker]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorker()
    {
        return $this->hasOne(User::className(), ['id' => 'worker_id']);
    }
}
