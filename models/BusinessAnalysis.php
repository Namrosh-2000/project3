<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Historia ya Uchambuzi — a single "Chambua Soko Langu" run saved for
 * an entrepreneur, so they can reopen it later from their dashboard.
 *
 * @property int $id
 * @property int $user_id
 * @property string $business_type
 * @property string|null $business_vision
 * @property string|null $target_customers
 * @property string $ward
 * @property int|null $location_id
 * @property int|null $starting_budget
 * @property int $rental_budget
 * @property string|null $space_size_needed
 * @property string|null $special_requirements
 * @property string $status
 * @property string|null $results_snapshot
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 * @property Location $location
 */
class BusinessAnalysis extends ActiveRecord
{
    const STATUS_COMPLETED = 'completed';

    public static function tableName()
    {
        return '{{%business_analysis}}';
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
            [['user_id', 'business_type', 'ward', 'rental_budget'], 'required'],
            [['user_id', 'location_id', 'starting_budget', 'rental_budget'], 'integer'],
            [['business_vision', 'special_requirements', 'results_snapshot'], 'string'],
            [['business_type'], 'string', 'max' => 150],
            [['target_customers'], 'string', 'max' => 255],
            [['ward'], 'string', 'max' => 100],
            [['space_size_needed'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 20],
            [['status'], 'default', 'value' => self::STATUS_COMPLETED],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getLocation()
    {
        return $this->hasOne(Location::class, ['id' => 'location_id']);
    }

    /**
     * Decoded results_snapshot (the exact result objects computed at
     * the time this analysis ran), or null if none was stored.
     */
    public function getSnapshot()
    {
        if (empty($this->results_snapshot)) {
            return null;
        }
        $decoded = json_decode($this->results_snapshot, true);
        return is_array($decoded) ? $decoded : null;
    }

    /**
     * Status label shown to the user in Kiswahili/English per the
     * active application language.
     */
    public function getStatusLabel()
    {
        $labels = [
            self::STATUS_COMPLETED => Yii::t('app', 'fursa.history.status_completed'),
        ];
        return $labels[$this->status] ?? $this->status;
    }
}
