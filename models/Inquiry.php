<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Inquiry with reply support.
 *
 * @property int $id
 * @property int $property_id
 * @property int $seeker_id
 * @property int $owner_id
 * @property string $message
 * @property string|null $phone
 * @property string $status
 * @property string|null $reply
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Property $property
 * @property User $seeker
 * @property User $owner
 */
class Inquiry extends ActiveRecord
{
    const STATUS_NEW = 'new';
    const STATUS_READ = 'read';
    const STATUS_REPLIED = 'replied';

    public static function tableName()
    {
        return '{{%inquiry}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['property_id', 'seeker_id', 'owner_id', 'message'], 'required'],
            [['property_id', 'seeker_id', 'owner_id'], 'integer'],
            [['message', 'reply'], 'string'],
            [['status'], 'string', 'max' => 20],
            [['phone'], 'string', 'max' => 20],
            [['status'], 'in', 'range' => [self::STATUS_NEW, self::STATUS_READ, self::STATUS_REPLIED]],
            [['status'], 'default', 'value' => self::STATUS_NEW],
            [['property_id'], 'exist', 'targetClass' => Property::class, 'targetAttribute' => 'id'],
            [['seeker_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
            [['owner_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
        ];
    }

    public function getProperty()
    {
        return $this->hasOne(Property::class, ['id' => 'property_id']);
    }

    public function getSeeker()
    {
        return $this->hasOne(User::class, ['id' => 'seeker_id']);
    }

    public function getOwner()
    {
        return $this->hasOne(User::class, ['id' => 'owner_id']);
    }

    public function markRead()
    {
        if ($this->status === self::STATUS_NEW) {
            $this->status = self::STATUS_READ;
            $this->save(false);
        }
    }
}