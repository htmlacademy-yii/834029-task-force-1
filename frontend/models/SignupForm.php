<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $name;
    public $email;
    public $password;
    public $city_id;


    /**
     * {@inheritdoc}
     */
    public function rules() : array
    {
        return [
            [['name', 'email'], 'trim'],
            [['name', 'email', 'password', 'city_id'], 'required'],
            [['name', 'email', 'password'], 'string'],
            [['password'], 'string', 'min' => 8],
            ['email', 'email'],
            [
                'email',
                'unique',
                'targetClass' => '\common\models\User',
                'message' => 'Пользователь с таким email уже существует'
            ],
            [
                'city_id',
                'exist',
                'targetClass' => '\common\models\City',
                'targetAttribute' => ['city_id' => 'id'],
                'message' => 'Неизвестный город'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() : array
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'Электронная почта',
            'password' => 'Пароль',
            'city_id' => 'Город проживания',
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful
     */
    public function signup() : ?bool
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password_hash = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        $user->city_id = $this->city_id;
        return $user->save();
    }

}
