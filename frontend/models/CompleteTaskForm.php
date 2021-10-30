<?php


namespace frontend\models;


use common\models\Review;
use common\models\Task;
use yii\base\Model;

class CompleteTaskForm extends Model
{
    public $isComplete;
    public $comment;
    public $rating;

    public const COMPLETE = 'yes';
    public const DIFFICULT = 'difficult';

    public function attributeLabels(): array
    {
        return [
            'comment' => 'Комментарий',
        ];
    }

    public function rules(): array
    {
        return [
            [['isComplete'], 'required'],
            [['isComplete'], 'in', 'range' => [self::COMPLETE, self::DIFFICULT]],
            [['comment'], 'string'],
            [['rating'], 'integer', 'min' => 1, 'max' => 5]
        ];
    }

    public function createReview(Task $task): bool
    {
        $review = new Review();
        $review->task_id = $task->id;
        $review->customer_id = $task->customer_id;
        $review->worker_id = $task->worker_id;
        $review->comment = $this->comment;
        $review->rating = $this->rating;

        if ($review->validate() && $review->save()) {
            // TODO отправить уведомление
            return true;
        }

        $this->addErrors($review->getErrors());
        return false;
    }
}