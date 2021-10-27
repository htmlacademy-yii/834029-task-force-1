<?php


namespace frontend\models;


use common\models\Category;
use common\models\City;
use common\models\Task;
use yii\base\Model;

class CreateTaskForm extends Model
{
    public $title;
    public $description;
    public $price;
    public $category_id;
    public $finish_at;
    public $city_id;
    public $attach_id;

    public function attributeLabels(): array
    {
        return [
            'title' => 'Мне нужно',
            'description' => 'Подробности задания',
            'price' => 'Бюджет',
            'category_id' => 'Категория',
            'finish_at' => 'Сроки исполнения',
            'city_id' => 'Локация',
            'attach_id' => 'Файлы',
        ];
    }

    public function rules(): array
    {
        return [
            [['title', 'description', 'category_id'], 'required'],
            [['title', 'description'], 'string'],
            [['title'], 'string', 'min' => 10],
            [['description'], 'string', 'min' => 30],
            [['price', 'category_id', 'city_id'], 'integer'],
            [['price'], 'compare', 'compareValue' => 0, 'operator' => '>'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    public function createTask($customer_id, $attach_id = null) : ?Task
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
        $task->attach_id = $attach_id;

        // TODO city_id, longitude, latitude

        if ($task->validate() && $task->save()) {
            return $task;
        }

        $this->addErrors($task->getErrors());

        return null;
    }
}