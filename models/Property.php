<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int $category_id
 * @property int $location_id
 * @property int $owner_id
 * @property string $listing_type
 * @property string $price
 * @property string|null $price_period
 * @property int|null $bedrooms
 * @property int $is_furnished
 * @property int $has_parking
 * @property int $has_water
 * @property int $has_electricity
 * @property int $has_security
 * @property int $has_internet
 * @property string $status
 * @property int $is_available
 * @property string|null $contract_terms
 * @property string|null $contract_file
 * @property int $created_at
 * @property int $updated_at
 *
 * @property PropertyCategory $category
 * @property Location $location
 * @property User $owner
 * @property PropertyImage[] $images
 */
class Property extends ActiveRecord
{
    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIED = 'verified';
    const STATUS_REJECTED = 'rejected';
    const STATUS_REPORTED = 'reported';

    public static function tableName()
    {
        return '{{%property}}';
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
            [['title', 'category_id', 'location_id', 'owner_id', 'listing_type', 'price'], 'required'],
            [['description', 'contract_terms'], 'string'],
            [['category_id', 'location_id', 'owner_id', 'bedrooms'], 'integer'],
            [['price'], 'number'],
            [['is_furnished', 'has_parking', 'has_water', 'has_electricity', 'has_security', 'has_internet', 'is_available'], 'boolean'],
            [['title'], 'string', 'max' => 150],
            [['contract_file'], 'string', 'max' => 255],
            [['listing_type', 'price_period', 'status'], 'string', 'max' => 20],
            [['listing_type'], 'in', 'range' => ['rent', 'sale']],
            [['price_period'], 'in', 'range' => ['monthly', 'yearly', 'one_time']],
            [['status'], 'in', 'range' => [self::STATUS_PENDING, self::STATUS_VERIFIED, self::STATUS_REJECTED, self::STATUS_REPORTED]],
            [['status'], 'default', 'value' => self::STATUS_PENDING],
            [['is_available'], 'default', 'value' => true],
            [['category_id'], 'exist', 'targetClass' => PropertyCategory::class, 'targetAttribute' => 'id'],
            [['location_id'], 'exist', 'targetClass' => Location::class, 'targetAttribute' => 'id'],
            [['owner_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(PropertyCategory::class, ['id' => 'category_id']);
    }

    public function getLocation()
    {
        return $this->hasOne(Location::class, ['id' => 'location_id']);
    }

    public function getOwner()
    {
        return $this->hasOne(User::class, ['id' => 'owner_id']);
    }

    public function getImages()
    {
        return $this->hasMany(PropertyImage::class, ['property_id' => 'id'])
            ->orderBy([
                PropertyImage::tableName() . '.sort_order' => SORT_ASC,
                PropertyImage::tableName() . '.id' => SORT_ASC
            ]);
    }

    public function getCoverImage()
    {
        return $this->hasOne(PropertyImage::class, ['property_id' => 'id'])
            ->orderBy([
                PropertyImage::tableName() . '.is_cover' => SORT_DESC,
                PropertyImage::tableName() . '.sort_order' => SORT_ASC,
                PropertyImage::tableName() . '.id' => SORT_ASC
            ]);
    }

    public function getFavorites()
    {
        return $this->hasMany(Favorite::class, ['property_id' => 'id']);
    }

    public function getInquiries()
    {
        return $this->hasMany(Inquiry::class, ['property_id' => 'id']);
    }

    public function getReports()
    {
        return $this->hasMany(Report::class, ['property_id' => 'id']);
    }
}
