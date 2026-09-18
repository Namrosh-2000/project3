<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $property_id
 * @property int $reporter_id
 * @property string $reason
 * @property string|null $details
 * @property string $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Property $property
 * @property User $reporter
 */
class Report extends ActiveRecord
{
    const STATUS_PENDING = 'pending';
    const STATUS_REVIEWED = 'reviewed';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_DISMISSED = 'dismissed';

    public static function tableName()
    {
        return '{{%report}}';
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
            [['property_id', 'reporter_id', 'reason'], 'required'],
            [['property_id', 'reporter_id'], 'integer'],
            [['details'], 'string'],
            [['reason'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 20],
            [['reason'], 'in', 'range' => ['fake', 'duplicate', 'misleading', 'other']],
            [['status'], 'in', 'range' => [self::STATUS_PENDING, self::STATUS_REVIEWED, self::STATUS_RESOLVED, self::STATUS_DISMISSED]],
            [['status'], 'default', 'value' => self::STATUS_PENDING],
            [['property_id'], 'exist', 'targetClass' => Property::class, 'targetAttribute' => 'id'],
            [['reporter_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
        ];
    }

    public function getProperty()
    {
        return $this->hasOne(Property::class, ['id' => 'property_id']);
    }

    public function getReporter()
    {
        return $this->hasOne(User::class, ['id' => 'reporter_id']);
    }
}
