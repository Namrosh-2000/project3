<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Where a piece of local-market data actually came from. Every
 * MarketIndicator points to one of these, so nothing shown in Fursa
 * is ever a bare, unattributed number.
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string|null $description
 * @property int $is_demo
 * @property string|null $collected_at
 * @property int $created_at
 * @property int $updated_at
 */
class LocalDataSource extends ActiveRecord
{
    const TYPE_MANUAL_OBSERVATION = 'manual_observation';
    const TYPE_POPULATION_ESTIMATE = 'population_estimate';
    const TYPE_ECONOMIC_INDICATOR = 'economic_indicator';
    const TYPE_COMPETITION_SURVEY = 'competition_survey';
    const TYPE_OTHER = 'other';

    public static function typeOptions()
    {
        return [
            self::TYPE_MANUAL_OBSERVATION => 'Manual Observation (uchunguzi wa mtaani)',
            self::TYPE_POPULATION_ESTIMATE => 'Population Estimate',
            self::TYPE_ECONOMIC_INDICATOR => 'Economic Indicator',
            self::TYPE_COMPETITION_SURVEY => 'Competition Survey',
            self::TYPE_OTHER => 'Other',
        ];
    }

    public static function tableName()
    {
        return '{{%local_data_source}}';
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
            [['name', 'type'], 'required'],
            [['description'], 'string'],
            [['is_demo'], 'boolean'],
            [['collected_at'], 'date', 'format' => 'php:Y-m-d'],
            [['name'], 'string', 'max' => 150],
            [['type'], 'string', 'max' => 30],
            [['type'], 'in', 'range' => array_keys(self::typeOptions())],
            [['is_demo'], 'default', 'value' => true],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Source Name',
            'type' => 'Source Type',
            'description' => 'Description',
            'is_demo' => 'This is demo/placeholder data',
            'collected_at' => 'Data Collected On',
        ];
    }

    public function getIndicators()
    {
        return $this->hasMany(MarketIndicator::class, ['data_source_id' => 'id']);
    }
}
