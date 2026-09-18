<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Search form for Property model.
 */
class PropertySearch extends Property
{
    public $keyword;
    public $min_price;
    public $max_price;

    public function rules()
    {
        return [
            [['keyword', 'listing_type', 'status'], 'string', 'max' => 200],
            [['min_price', 'max_price'], 'number'],
            [['category_id', 'location_id', 'bedrooms', 'owner_id'], 'integer'],
            [['is_furnished', 'has_parking', 'has_water', 'has_electricity', 'has_security', 'has_internet'], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'keyword' => 'Search',
            'min_price' => 'Min Price (TSh)',
            'max_price' => 'Max Price (TSh)',
        ];
    }

    public function search($params)
    {
        $query = Property::find()
            ->alias('p')
            ->joinWith(['category cat', 'location loc'])
            ->with(['coverImage'])
            ->where(['p.status' => Property::STATUS_VERIFIED])
            ->andWhere(['p.is_available' => true]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 12],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => [
                    'price' => ['asc' => ['p.price' => SORT_ASC], 'desc' => ['p.price' => SORT_DESC]],
                    'created_at' => ['asc' => ['p.created_at' => SORT_ASC], 'desc' => ['p.created_at' => SORT_DESC]],
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!empty($this->keyword)) {
            $query->andFilterWhere(['or',
                ['like', 'p.title', $this->keyword],
                ['like', 'p.description', $this->keyword],
                ['like', 'loc.ward', $this->keyword],
                ['like', 'loc.street', $this->keyword],
                ['like', 'cat.name', $this->keyword],
            ]);
        }

        $query->andFilterWhere(['p.category_id' => $this->category_id])
            ->andFilterWhere(['p.location_id' => $this->location_id])
            ->andFilterWhere(['p.owner_id' => $this->owner_id])
            ->andFilterWhere(['p.listing_type' => $this->listing_type])
            ->andFilterWhere(['p.bedrooms' => $this->bedrooms])
            ->andFilterWhere(['p.is_furnished' => $this->is_furnished])
            ->andFilterWhere(['p.has_parking' => $this->has_parking])
            ->andFilterWhere(['p.has_water' => $this->has_water])
            ->andFilterWhere(['p.has_electricity' => $this->has_electricity])
            ->andFilterWhere(['p.has_security' => $this->has_security])
            ->andFilterWhere(['p.has_internet' => $this->has_internet])
            ->andFilterWhere(['>=', 'p.price', $this->min_price])
            ->andFilterWhere(['<=', 'p.price', $this->max_price]);

        return $dataProvider;
    }
}