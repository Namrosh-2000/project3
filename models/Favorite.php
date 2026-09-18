<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $user_id
 * @property int $property_id
 * @property int $created_at
 *
 * @property User $user
 * @property Property $property
 */
class Favorite extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%favorite}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'property_id'], 'required'],
            [['user_id', 'property_id'], 'integer'],
            [['user_id', 'property_id'], 'unique', 'targetAttribute' => ['user_id', 'property_id']],
            [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
            [['property_id'], 'exist', 'targetClass' => Property::class, 'targetAttribute' => 'id'],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getProperty()
    {
        return $this->hasOne(Property::class, ['id' => 'property_id']);
    }
}
