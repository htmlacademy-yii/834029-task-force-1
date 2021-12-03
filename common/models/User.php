<?php

namespace common\models;

use DateTime;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;
use yii\web\IdentityInterface;

class User extends base\User implements IdentityInterface
{
    public const WORKER_ROLE = 'worker';
    public const CUSTOMER_ROLE = 'customer';
    public const NOW_ONLINE_MINUTES = 30;

    protected array $category_ids;

    public function getFavoriteUsers() : ActiveQuery
    {
        return $this->hasMany(Favorite::class, ['customer_id' => 'id']);
    }

    public function getCustomerReviews() : ActiveQuery
    {
        return $this->hasMany(Review::class, ['customer_id' => 'id']);
    }

    public function getWorkerReviews() : ActiveQuery
    {
        return $this->hasMany(Review::class, ['worker_id' => 'id']);
    }

    public function getCustomerTasks() : ActiveQuery
    {
        return $this->hasMany(Task::class, ['customer_id' => 'id']);
    }

    public function getWorkerTasks() : ActiveQuery
    {
        return $this->hasMany(Task::class, ['worker_id' => 'id']);
    }

    public function getWorkerRating() : string
    {
        $rating_array = ArrayHelper::map($this->workerReviews, 'id', 'rating');
        $review_count = count($rating_array);

        if($review_count === 0) {
            return 0;
        }

        return array_sum($rating_array) / $review_count;
    }

    public function getLastActivity() : string
    {
        $minutes = (time() - strtotime($this->last_active_time)) / 60;
        if($minutes <= self::NOW_ONLINE_MINUTES) {
            return 'Сейчас онлайн';
        }
        return 'Был на сайте ' . Yii::$app->formatter->asRelativeTime($this->last_active_time);
    }

    public function getAge() : string
    {
        if(!$this->birthday) {
            return 'возраст не указан';
        }

        $birthday = new DateTime($this->birthday);
        $diff = (new DateTime())->diff($birthday);
        return Yii::t(
            'app',
            '{n, plural, one{# год} few{# лет} many{# лет} other{# лет}}',
            ['n' => $diff->y]
        );
    }

    public function getRegisterDuration() : string
    {
        $now = new DateTime();
        $register = new DateTime($this->register_at);
        $diff = $register->diff($now);

        if($diff->y) {
            $diff = 'P'.$diff->y.'Y';
        } elseif($diff->m) {
            $diff = 'P'.$diff->m.'M';
        } elseif($diff->d) {
            $diff = 'P'.$diff->d.'D';
        } elseif($diff->h) {
            $diff = 'P'.$diff->h.'H';
        } elseif($diff->m) {
            $diff = 'P'.$diff->m.'M';
        }

        return Yii::$app->formatter->asDuration($diff) . ' на сайте';
    }

    public function isCustomer() : bool
    {
        return $this->role === self::CUSTOMER_ROLE;
    }

    public function isWorker() : bool
    {
        return $this->role === self::WORKER_ROLE;
    }

    public function validatePassword($password) : bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public static function findIdentity($id) : ActiveRecord
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    public function getCategoryIds(): array
    {
        return $this->category_ids;
    }

    public function setCategoryIds(array $category_ids): void
    {
        $this->category_ids = $category_ids;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (isset($this->category_ids)) {
            UserCategory::deleteAll(['user_id' => $this->id]);
            $values = [];
            foreach ($this->category_ids as $id) {
                $values[] = [$this->id, $id];
            }
            self::getDb()->createCommand()
                ->batchInsert(UserCategory::tableName(), ['user_id', 'category_id'], $values)
                ->execute();
        }

        parent::afterSave($insert, $changedAttributes);
    }
}
