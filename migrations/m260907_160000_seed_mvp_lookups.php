<?php

use yii\db\Migration;

/**
 * Seeds MVP lookup data: property categories and Kinondoni locations.
 */
class m260907_160000_seed_mvp_lookups extends Migration
{
    public function safeUp()
    {
        $now = time();

        $this->batchInsert('{{%property_category}}', ['name', 'slug', 'type', 'description', 'is_active', 'created_at', 'updated_at'], [
            ['Room', 'room', 'room', 'Single rooms for rent', 1, $now, $now],
            ['Apartment', 'apartment', 'apartment', 'Apartments and flats', 1, $now, $now],
            ['House', 'house', 'house', 'Standalone houses', 1, $now, $now],
            ['Business Space', 'business-space', 'business_space', 'Shops, offices, and commercial spaces', 1, $now, $now],
        ]);

        $wards = [
            'Kinondoni',
            'Msasani',
            'Mikocheni',
            'Kijitonyama',
            'Mwenge',
            'Makumbusho',
            'Mwananyamala',
            'Magomeni',
            'Ndugumbi',
            'Tandale',
        ];

        $rows = [];
        foreach ($wards as $ward) {
            $rows[] = ['Dar es Salaam', 'Kinondoni', $ward, null, null, null, $now, $now];
        }

        $this->batchInsert(
            '{{%location}}',
            ['region', 'municipality', 'ward', 'street', 'latitude', 'longitude', 'created_at', 'updated_at'],
            $rows
        );
    }

    public function safeDown()
    {
        $this->delete('{{%property_category}}', ['slug' => ['room', 'apartment', 'house', 'business-space']]);
        $this->delete('{{%location}}', ['municipality' => 'Kinondoni']);
    }
}
