<?php


namespace frontend\models;


use common\models\Category;
use common\models\City;
use common\models\File;
use common\models\Task;
use taskforce\models\dto\LocationDto;
use yii\base\Model;

class CreateTaskForm extends Model
{
    public $title;
    public $description;
    public $price;
    public $category_id;
    public $finish_at;
    public $location;
    public $attach_id;

    public function attributeLabels(): array
    {
        return [
            'title' => 'Мне нужно',
            'description' => 'Подробности задания',
            'price' => 'Бюджет',
            'category_id' => 'Категория',
            'finish_at' => 'Сроки исполнения',
            'location' => 'Локация',
            'attach_id' => 'Файлы',
        ];
    }

    public function rules(): array
    {
        return [
            [['title', 'description', 'category_id'], 'required'],
            [['title', 'description', 'location'], 'string'],
            [['title'], 'string', 'min' => 10],
            [['description'], 'string', 'min' => 30],
            [['price', 'category_id'], 'integer'],
            [['price'], 'compare', 'compareValue' => 0, 'operator' => '>'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    public function createTask(int $customer_id, ?LocationDto $location, ?string $attach_id = null) : ?Task
    {
        $task = new Task();
        $task->title = $this->title;
        $task->description = $this->description;
        $task->description = $this->description;
        $task->category_id = $this->category_id;
        $task->price = $this->price;
        $task->finish_at = $this->finish_at;
        $task->status = \taskforce\models\Task::STATUS_NEW;
        $task->customer_id = $customer_id;

        $isset_files = File::find()->where(['attach_id' => $attach_id])->count();
        if ($isset_files) {
            $task->attach_id = $attach_id;
        }

        if ($location) {
            $task->latitude = $location->latitude;
            $task->longitude = $location->longitude;
            $task->city_id = $location->city_id;
        }

        if ($task->validate() && $task->save()) {
            return $task;
        }

        $this->addErrors($task->getErrors());

        return null;
    }
}