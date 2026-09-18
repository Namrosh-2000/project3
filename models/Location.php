<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $region
 * @property string $municipality
 * @property string $ward
 * @property string|null $street
 * @property string|null $latitude
 * @property string|null $longitude
 * @property int $created_at
 * @property int $updated_at
 */
class Location extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%location}}';
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
            [['ward'], 'required'],
            [['latitude', 'longitude'], 'number'],
            [['region', 'municipality', 'ward'], 'string', 'max' => 100],
            [['street'], 'string', 'max' => 150],
            [['region'], 'default', 'value' => 'Dar es Salaam'],
            [['municipality'], 'default', 'value' => 'Kinondoni'],
        ];
    }

    public function getProperties()
    {
        return $this->hasMany(Property::class, ['location_id' => 'id']);
    }

    public function getDisplayName()
    {
        $parts = array_filter([$this->ward, $this->street, $this->municipality]);
        return implode(', ', $parts);
    }
}
