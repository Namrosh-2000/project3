<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $property_id
 * @property string $image_path
 * @property int $is_cover
 * @property int $sort_order
 * @property int $created_at
 *
 * @property Property $property
 */
class PropertyImage extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%property_image}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['property_id', 'image_path'], 'required'],
            [['property_id', 'sort_order'], 'integer'],
            [['is_cover'], 'boolean'],
            [['image_path'], 'string', 'max' => 255],
            [['is_cover'], 'default', 'value' => false],
            [['sort_order'], 'default', 'value' => 0],
            [['property_id'], 'exist', 'targetClass' => Property::class, 'targetAttribute' => 'id'],
        ];
    }

    public function getProperty()
    {
        return $this->hasOne(Property::class, ['id' => 'property_id']);
    }

    public function getImageUrl()
    {
        if (empty($this->image_path)) {
            return '';
        }
        if (strpos($this->image_path, 'http://') === 0 || strpos($this->image_path, 'https://') === 0) {
            return $this->image_path;
        }
        $web = \Yii::getAlias('@web', false) ?: '';
        $path = '/' . ltrim($this->image_path, '/');
        if (!empty($web) && strpos($path, $web) !== 0) {
            return $web . $path;
        }
        return $path;
    }
}
