<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Booking model representing site visit or property reservation requests.
 *
 * @property int $id
 * @property string $booking_code
 * @property int $property_id
 * @property int $seeker_id
 * @property int $owner_id
 * @property string $booking_type
 * @property string $booking_date
 * @property string $booking_time
 * @property string|null $seeker_phone
 * @property string|null $notes
 * @property string $status
 * @property string|null $owner_response_notes
 * @property string|null $proposed_date
 * @property string|null $proposed_time
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Property $property
 * @property User $seeker
 * @property User $owner
 */
class Booking extends ActiveRecord
{
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_RESCHEDULED = 'rescheduled';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    const TYPE_VISIT = 'visit';
    const TYPE_RESERVATION = 'reservation';

    const BARGAIN_STATUS_NONE = 'none';
    const BARGAIN_STATUS_PENDING = 'pending';
    const BARGAIN_STATUS_ACCEPTED = 'accepted';
    const BARGAIN_STATUS_REJECTED = 'rejected';

    public static function tableName()
    {
        return '{{%booking}}';
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
            [['property_id', 'seeker_id', 'owner_id', 'booking_type', 'booking_date', 'booking_time'], 'required'],
            [['property_id', 'seeker_id', 'owner_id', 'created_at', 'updated_at'], 'integer'],
            [['offered_price', 'agreed_price'], 'number'],
            [['notes', 'owner_response_notes'], 'string'],
            [['booking_code'], 'string', 'max' => 20],
            [['booking_type', 'booking_time', 'seeker_phone', 'status', 'proposed_time', 'bargain_status'], 'string', 'max' => 20],
            [['booking_date', 'proposed_date'], 'safe'],
            [['booking_type'], 'in', 'range' => [self::TYPE_VISIT, self::TYPE_RESERVATION]],
            [['bargain_status'], 'in', 'range' => [
                self::BARGAIN_STATUS_NONE,
                self::BARGAIN_STATUS_PENDING,
                self::BARGAIN_STATUS_ACCEPTED,
                self::BARGAIN_STATUS_REJECTED,
            ]],
            [['status'], 'in', 'range' => [
                self::STATUS_PENDING,
                self::STATUS_CONFIRMED,
                self::STATUS_RESCHEDULED,
                self::STATUS_REJECTED,
                self::STATUS_COMPLETED,
                self::STATUS_CANCELLED,
            ]],
            [['status'], 'default', 'value' => self::STATUS_PENDING],
            [['bargain_status'], 'default', 'value' => self::BARGAIN_STATUS_NONE],
            [['booking_type'], 'default', 'value' => self::TYPE_VISIT],
            [['booking_code'], 'unique'],
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert && empty($this->booking_code)) {
            $this->booking_code = self::generateBookingCode();
        }
        return parent::beforeSave($insert);
    }

    public static function generateBookingCode()
    {
        do {
            $code = 'BK-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        } while (self::find()->where(['booking_code' => $code])->exists());

        return $code;
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

    public function getEffectivePrice()
    {
        if ($this->agreed_price !== null && (float)$this->agreed_price > 0) {
            return (float)$this->agreed_price;
        }
        return (float)($this->property->price ?? 0);
    }

    public function getStatusLabel()
    {
        $labels = [
            self::STATUS_PENDING => 'Inasubiri (Pending)',
            self::STATUS_CONFIRMED => 'Imehakikishwa (Confirmed)',
            self::STATUS_RESCHEDULED => 'Imebadilishwa Tarehe (Rescheduled)',
            self::STATUS_REJECTED => 'Imekataliwa (Rejected)',
            self::STATUS_COMPLETED => 'Imekamilika (Completed)',
            self::STATUS_CANCELLED => 'Imeahirishwa (Cancelled)',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusBadgeClass()
    {
        $classes = [
            self::STATUS_PENDING => 'bg-warning text-dark',
            self::STATUS_CONFIRMED => 'bg-success text-white',
            self::STATUS_RESCHEDULED => 'bg-info text-dark',
            self::STATUS_REJECTED => 'bg-danger text-white',
            self::STATUS_COMPLETED => 'bg-primary text-white',
            self::STATUS_CANCELLED => 'bg-secondary text-white',
        ];

        return $classes[$this->status] ?? 'bg-secondary text-white';
    }

    public function getTypeLabel()
    {
        return $this->booking_type === self::TYPE_RESERVATION 
            ? 'Kuweka Oda (Reservation)' 
            : 'Kukagua Eneo (Site Visit)';
    }

    public function getBargainStatusLabel()
    {
        $labels = [
            self::BARGAIN_STATUS_NONE => 'Hakuna Bargain',
            self::BARGAIN_STATUS_PENDING => 'Offa Inasubiri Review',
            self::BARGAIN_STATUS_ACCEPTED => 'Offa Imekubaliwa',
            self::BARGAIN_STATUS_REJECTED => 'Offa Imekataliwa',
        ];
        return $labels[$this->bargain_status] ?? ucfirst($this->bargain_status);
    }
}
