<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * A single admin-entered local-market reading for a ward (and,
 * optionally, a specific business category): a competition level, a
 * population estimate, a risk note, and so on.
 *
 * `indicator_type` reuses the seven Fursa-preview labels already
 * translated in Phase 1 (`fursa.item_demand`, `fursa.item_competition`,
 * `fursa.item_market_strength`, `fursa.item_gaps`, `fursa.item_fit`,
 * `fursa.item_risk`, `fursa.item_density`) so a reading here always
 * has a ready-made, correctly-translated label — see getTypeLabel().
 *
 * @property int $id
 * @property int $location_id
 * @property int|null $category_id
 * @property int $data_source_id
 * @property string $indicator_type
 * @property string|null $value_numeric
 * @property string|null $value_label
 * @property string $confidence_level
 * @property string|null $notes
 * @property int $is_demo
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Location $location
 * @property PropertyCategory|null $category
 * @property LocalDataSource $dataSource
 */
class MarketIndicator extends ActiveRecord
{
    const TYPE_DEMAND = 'demand';
    const TYPE_COMPETITION = 'competition';
    const TYPE_MARKET_STRENGTH = 'market_strength';
    const TYPE_GAPS = 'gaps';
    const TYPE_FIT = 'fit';
    const TYPE_RISK = 'risk';
    const TYPE_DENSITY = 'density';

    const CONFIDENCE_LOW = 'low';
    const CONFIDENCE_MEDIUM = 'medium';
    const CONFIDENCE_HIGH = 'high';

    public static function typeOptions()
    {
        return [
            self::TYPE_DEMAND => 'Dalili za Mahitaji',
            self::TYPE_COMPETITION => 'Ushindani',
            self::TYPE_MARKET_STRENGTH => 'Nguvu ya Soko',
            self::TYPE_GAPS => 'Mapengo ya Fursa',
            self::TYPE_FIT => 'Ulinganifu wa Eneo',
            self::TYPE_RISK => 'Viashiria vya Hatari',
            self::TYPE_DENSITY => 'Msongamano wa Biashara',
        ];
    }

    public static function confidenceOptions()
    {
        return [
            self::CONFIDENCE_LOW => 'Chini (Low)',
            self::CONFIDENCE_MEDIUM => 'Wastani (Medium)',
            self::CONFIDENCE_HIGH => 'Juu (High)',
        ];
    }

    public static function tableName()
    {
        return '{{%market_indicator}}';
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
            [['location_id', 'data_source_id', 'indicator_type'], 'required'],
            [['location_id', 'category_id', 'data_source_id'], 'integer'],
            [['value_numeric'], 'number'],
            [['notes'], 'string'],
            [['value_label'], 'string', 'max' => 100],
            [['indicator_type'], 'string', 'max' => 30],
            [['indicator_type'], 'in', 'range' => array_keys(self::typeOptions())],
            [['confidence_level'], 'string', 'max' => 10],
            [['confidence_level'], 'in', 'range' => array_keys(self::confidenceOptions())],
            [['confidence_level'], 'default', 'value' => self::CONFIDENCE_LOW],
            [['is_demo'], 'boolean'],
            [['is_demo'], 'default', 'value' => true],
            [['category_id'], 'default', 'value' => null],
        ];
    }

    public function attributeLabels()
    {
        return [
            'location_id' => 'Ward',
            'category_id' => 'Business Category (optional — leave blank to apply to whole ward)',
            'data_source_id' => 'Data Source',
            'indicator_type' => 'Indicator Type',
            'value_numeric' => 'Numeric Value (optional)',
            'value_label' => 'Label / Reading (optional, e.g. "Juu", "Wastani")',
            'confidence_level' => 'Confidence Level',
            'notes' => 'Notes',
            'is_demo' => 'This is demo/placeholder data',
        ];
    }

    public function getLocation()
    {
        return $this->hasOne(Location::class, ['id' => 'location_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(PropertyCategory::class, ['id' => 'category_id']);
    }

    public function getDataSource()
    {
        return $this->hasOne(LocalDataSource::class, ['id' => 'data_source_id']);
    }

    /**
     * The Kiswahili label already used by the Fursa preview section
     * (fursa.item_*), so a reading and its marketing copy never drift
     * apart into two different vocabularies.
     */
    public function getTypeLabel()
    {
        return Yii::t('app', 'fursa.item_' . ($this->indicator_type === self::TYPE_GAPS ? 'gaps' : $this->indicator_type));
    }

    public function getConfidenceLabel()
    {
        return Yii::t('app', 'localdata.confidence_' . $this->confidence_level);
    }

    /**
     * What to actually print for this reading — the qualitative label
     * if one was entered, else the numeric value, else nothing (never
     * a fabricated placeholder).
     */
    public function getDisplayValue()
    {
        if ($this->value_label !== null && $this->value_label !== '') {
            return $this->value_label;
        }
        if ($this->value_numeric !== null) {
            return number_format((float)$this->value_numeric);
        }
        return null;
    }
}
