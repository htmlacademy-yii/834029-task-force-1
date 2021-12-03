<?php

namespace frontend\models;

use common\models\Category;
use common\models\City;
use common\models\User;
use common\models\UserCategory;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

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
            [['avatar'], 'file', 'extensions' => 'png, jpg'],
            ['phone', 'string', 'length' => 11],
            ['skype', 'string', 'min' => 3],
            ['telegram', 'string', 'min' => 1],
            ['birthday', 'date', 'format' => 'yyyy-MM-dd'],
            [['address'], 'exist', 'targetClass' => City::class, 'targetAttribute' => ['address' => 'id']],
            [['category'], 'each', 'rule' => [
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => Category::class,
                    'targetAttribute' => ['category' => 'id']
                ]
            ],
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

    public function updateUser(User $user): ?User
    {
        if ($this->validate()) {
            $this->updateInformation($user);
            $this->updateAvatar($user);
            $this->updatePassword($user);
            $this->updateNotifySettings($user);
            $this->updateSpecializations($user);

            $user->save();
            return $user;
        }

        return null;
    }

    private function updateInformation(User $user)
    {
        $user->name = $this->name;
        $user->email = $this->email;
        $user->city_id = $this->address;
        $user->birthday = $this->birthday;
        $user->about = $this->about;
        $user->phone = $this->phone;
        $user->skype = $this->skype;
        $user->telegram = $this->telegram;
    }

    private function updateAvatar(User $user)
    {
        if ($this->avatar) {
            $upload_dir = Yii::getAlias('@webroot/uploads/avatar');
            if(!file_exists($upload_dir)) {
                FileHelper::createDirectory($upload_dir);
            }

            $new_name = Yii::$app->security->generateRandomString(8) . '.' . $this->avatar->getExtension();
            $this->avatar->saveAs($upload_dir . '/' . $new_name);
            $user->avatar = $new_name;
        }
    }

    private function updatePassword(User $user): void
    {
        if ($this->password) {
            $user->password_hash = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        }
    }

    private function updateNotifySettings(User $user): void
    {
        $user->is_show_profile = $this->is_show_profile;
        $user->is_show_contacts = $this->is_show_contacts;
        $user->is_notify_about_message = $this->is_notify_about_message;
        $user->is_notify_about_action = $this->is_notify_about_action;
        $user->is_notify_about_review = $this->is_notify_about_review;
    }

    private function updateSpecializations(User $user)
    {
        if (!empty($this->category)) {
            $user->role = User::WORKER_ROLE;
            $user->setCategoryIds($this->category);
        } else {
            $user->role = User::CUSTOMER_ROLE;
            $user->setCategoryIds([]);
        }
    }
}
