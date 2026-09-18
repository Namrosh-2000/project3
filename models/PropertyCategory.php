<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string|null $description
 * @property int $is_active
 * @property int $created_at
 * @property int $updated_at
 */
class PropertyCategory extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%property_category}}';
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
            [['name', 'slug', 'type'], 'required'],
            [['description'], 'string'],
            [['is_active'], 'boolean'],
            [['name'], 'string', 'max' => 100],
            [['slug'], 'string', 'max' => 120],
            [['type'], 'string', 'max' => 30],
            [['type'], 'in', 'range' => ['room', 'apartment', 'house', 'business_space']],
            [['slug'], 'unique'],
            [['is_active'], 'default', 'value' => true],
        ];
    }

    public function getProperties()
    {
        return $this->hasMany(Property::class, ['category_id' => 'id']);
    }
}
