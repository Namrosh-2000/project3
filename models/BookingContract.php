<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * BookingContract model representing formal lease or purchase agreement contracts between Owner and Seeker.
 *
 * @property int $id
 * @property string $contract_code
 * @property int $booking_id
 * @property int $property_id
 * @property int $seeker_id
 * @property int $owner_id
 * @property string|null $contract_terms
 * @property string $status
 * @property string|null $seeker_signature
 * @property int|null $seeker_signed_at
 * @property string|null $owner_signature
 * @property int|null $owner_signed_at
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Booking $booking
 * @property Property $property
 * @property User $seeker
 * @property User $owner
 */
class BookingContract extends ActiveRecord
{
    const STATUS_PENDING_SIGNATURE = 'pending_signature';
    const STATUS_SIGNED_BY_SEEKER = 'signed_by_seeker';
    const STATUS_SIGNED_BY_OWNER = 'signed_by_owner';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public static function tableName()
    {
        return '{{%booking_contract}}';
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
            [['booking_id', 'property_id', 'seeker_id', 'owner_id'], 'required'],
            [['booking_id', 'property_id', 'seeker_id', 'owner_id', 'seeker_signed_at', 'owner_signed_at', 'created_at', 'updated_at'], 'integer'],
            [['contract_terms'], 'string'],
            [['contract_code'], 'string', 'max' => 25],
            [['status'], 'string', 'max' => 20],
            [['seeker_signature', 'owner_signature'], 'string', 'max' => 255],
            [['status'], 'in', 'range' => [
                self::STATUS_PENDING_SIGNATURE,
                self::STATUS_SIGNED_BY_SEEKER,
                self::STATUS_SIGNED_BY_OWNER,
                self::STATUS_COMPLETED,
                self::STATUS_CANCELLED,
            ]],
            [['status'], 'default', 'value' => self::STATUS_PENDING_SIGNATURE],
            [['contract_code'], 'unique'],
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert && empty($this->contract_code)) {
            $this->contract_code = self::generateContractCode();
        }
        return parent::beforeSave($insert);
    }

    public static function generateContractCode()
    {
        do {
            $code = 'CTR-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 7));
        } while (self::find()->where(['contract_code' => $code])->exists());

        return $code;
    }

    public function getBooking()
    {
        return $this->hasOne(Booking::class, ['id' => 'booking_id']);
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

    public function getStatusLabel()
    {
        $labels = [
            self::STATUS_PENDING_SIGNATURE => 'Inasubiri Saini (Pending Signature)',
            self::STATUS_SIGNED_BY_SEEKER => 'Imesainiwa na Mpangaji (Signed by Seeker)',
            self::STATUS_SIGNED_BY_OWNER => 'Imesainiwa na Mmiliki (Signed by Owner)',
            self::STATUS_COMPLETED => 'Imekamilika & Imesainiwa Wote (Fully Executed)',
            self::STATUS_CANCELLED => 'Imefutwa (Cancelled)',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusBadgeClass()
    {
        $classes = [
            self::STATUS_PENDING_SIGNATURE => 'bg-warning text-dark',
            self::STATUS_SIGNED_BY_SEEKER => 'bg-info text-dark',
            self::STATUS_SIGNED_BY_OWNER => 'bg-primary text-white',
            self::STATUS_COMPLETED => 'bg-success text-white',
            self::STATUS_CANCELLED => 'bg-secondary text-white',
        ];

        return $classes[$this->status] ?? 'bg-secondary text-white';
    }
}
