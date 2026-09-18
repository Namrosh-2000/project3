<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Payment model – Phase 7D.
 *
 * Lifecycle:  pending → paid | failed | cancelled
 *
 * Only one non-cancelled payment per booking is allowed at a time.
 * Use Payment::findActive($bookingId) to check before creating.
 *
 * @property int         $id
 * @property string      $payment_code
 * @property int         $booking_id
 * @property int         $seeker_id
 * @property int         $owner_id
 * @property float       $amount
 * @property string      $currency
 * @property string|null $transaction_id
 * @property string|null $payment_method
 * @property string      $status
 * @property string|null $failure_reason
 * @property int|null    $paid_at
 * @property int         $created_at
 * @property int         $updated_at
 *
 * @property Booking     $booking
 * @property User        $seeker
 * @property User        $owner
 */
class Payment extends ActiveRecord
{
    const STATUS_PENDING   = 'pending';
    const STATUS_PAID      = 'paid';
    const STATUS_FAILED    = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    const METHOD_MPESA        = 'mpesa';
    const METHOD_TIGOPESA     = 'tigopesa';
    const METHOD_AIRTEL_MONEY = 'airtel_money';
    const METHOD_BANK         = 'bank_transfer';
    const METHOD_CASH         = 'cash';
    const METHOD_OTHER        = 'other';

    public static function tableName()
    {
        return '{{%payment}}';
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
            [['booking_id', 'seeker_id', 'owner_id', 'amount'], 'required'],
            [['booking_id', 'seeker_id', 'owner_id', 'paid_at', 'created_at', 'updated_at'], 'integer'],
            [['amount'], 'number', 'min' => 1],
            [['failure_reason'], 'string'],
            [['payment_code'], 'string', 'max' => 30],
            [['currency'], 'string', 'max' => 3],
            [['transaction_id'], 'string', 'max' => 100],
            [['payment_method'], 'string', 'max' => 30],
            [['status'], 'string', 'max' => 20],
            [['currency'], 'default', 'value' => 'TZS'],
            [['status'], 'default', 'value' => self::STATUS_PENDING],
            [['status'], 'in', 'range' => [
                self::STATUS_PENDING,
                self::STATUS_PAID,
                self::STATUS_FAILED,
                self::STATUS_CANCELLED,
            ]],
            [['payment_method'], 'in', 'range' => [
                self::METHOD_MPESA,
                self::METHOD_TIGOPESA,
                self::METHOD_AIRTEL_MONEY,
                self::METHOD_BANK,
                self::METHOD_CASH,
                self::METHOD_OTHER,
            ], 'skipOnEmpty' => true],
            [['payment_code'], 'unique'],
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert && empty($this->payment_code)) {
            $this->payment_code = self::generatePaymentCode();
        }
        return parent::beforeSave($insert);
    }

    // ----------------------------------------------------------------
    // Queries
    // ----------------------------------------------------------------

    /**
     * Find the single active (non-cancelled) payment for a booking.
     */
    public static function findActive(int $bookingId): ?self
    {
        return static::find()
            ->where(['booking_id' => $bookingId])
            ->andWhere(['not', ['status' => self::STATUS_CANCELLED]])
            ->one();
    }

    /**
     * Generate a unique human-readable payment reference.
     */
    public static function generatePaymentCode(): string
    {
        do {
            $code = 'PAY-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        } while (static::find()->where(['payment_code' => $code])->exists());

        return $code;
    }

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    public function getBooking()
    {
        return $this->hasOne(Booking::class, ['id' => 'booking_id']);
    }

    public function getSeeker()
    {
        return $this->hasOne(User::class, ['id' => 'seeker_id']);
    }

    public function getOwner()
    {
        return $this->hasOne(User::class, ['id' => 'owner_id']);
    }

    // ----------------------------------------------------------------
    // Presentation helpers
    // ----------------------------------------------------------------

    public function getStatusLabel(): string
    {
        $labels = [
            self::STATUS_PENDING   => 'Inasubiri Malipo',
            self::STATUS_PAID      => 'Imelipwa',
            self::STATUS_FAILED    => 'Imeshindwa',
            self::STATUS_CANCELLED => 'Imefutwa',
        ];
        return $labels[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusBadgeClass(): string
    {
        $classes = [
            self::STATUS_PENDING   => 'bg-warning text-dark',
            self::STATUS_PAID      => 'bg-success text-white',
            self::STATUS_FAILED    => 'bg-danger text-white',
            self::STATUS_CANCELLED => 'bg-secondary text-white',
        ];
        return $classes[$this->status] ?? 'bg-secondary text-white';
    }

    public function getMethodLabel(): string
    {
        $labels = [
            self::METHOD_MPESA        => 'M-Pesa',
            self::METHOD_TIGOPESA     => 'Tigo Pesa',
            self::METHOD_AIRTEL_MONEY => 'Airtel Money',
            self::METHOD_BANK         => 'Benki / Bank Transfer',
            self::METHOD_CASH         => 'Fedha Taslimu (Cash)',
            self::METHOD_OTHER        => 'Njia Nyingine',
        ];
        return $labels[$this->payment_method] ?? ucfirst($this->payment_method ?? 'N/A');
    }

    public function getFormattedAmount(): string
    {
        return $this->currency . ' ' . number_format((float)$this->amount);
    }
}

