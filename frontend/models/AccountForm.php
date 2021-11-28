<?php

namespace frontend\models;

use common\models\Category;
use common\models\City;
use yii\base\Model;

class AccountForm extends Model
{
    public $name;
    public $email;
    public $avatar;
    public $address;
    public $birthday;
    public $about;
    public $category;
    public $password;
    public $repeat_password;
    public $phone;
    public $skype;
    public $telegram;
    public $is_show_profile;
    public $is_show_contacts;
    public $is_notify_about_message;
    public $is_notify_about_action;
    public $is_notify_about_review;

    public function rules(): array
    {
        return [
            [['name', 'email'], 'required'],
            ['email', 'email'],
            ['password', 'compare', 'compareAttribute' => 'repeat_password'],
            [
                [
                    'name',
                    'email',
                    'avatar',
                    'address',
                    'about',
                    'password',
                    'repeat_password',
                    'skype',
                    'telegram'
                ],
                'string'
            ],
            [
                [
                    'is_show_profile',
                    'is_show_contacts',
                    'is_notify_about_message',
                    'is_notify_about_action',
                    'is_notify_about_review'
                ],
                'boolean'
            ],
            ['phone', 'string', 'length' => 11],
            ['skype', 'string', 'min' => 3],
            ['telegram', 'string', 'min' => 1],
            ['birthday', 'date', 'format' => 'dd.MM.yyyy'],
            [['address'], 'exist', 'targetClass' => City::class, 'targetAttribute' => ['address' => 'id']],
            [['category'], 'exist', 'targetClass' => Category::class, 'targetAttribute' => ['category' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'email',
            'avatar' => 'Сменить аватар',
            'address' => 'Адрес',
            'birthday' => 'День рождения',
            'about' => 'Информация о себе',
            'category' => 'Выберите свои специализации',
            'password' => 'Новый пароль',
            'repeat_password' => 'Повтор пароля',
            'phone' => 'Телефон',
            'skype' => 'Skype',
            'telegram' => 'Telegram',
            'is_show_profile' => 'Не показывать мой профиль',
            'is_show_contacts' => 'Показывать мои контакты только заказчику',
            'is_notify_about_message' => 'Новое сообщение',
            'is_notify_about_action' => 'Действия по заданию',
            'is_notify_about_review' => 'Новый отзыв',
        ];
    }

    public function updateAvatar()
    {

    }
}
