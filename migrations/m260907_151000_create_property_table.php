<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%property}}`.
 */
class m260907_151000_create_property_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%property}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(150)->notNull(),
            'description' => $this->text()->null(),
            'category_id' => $this->integer()->notNull(),
            'location_id' => $this->integer()->notNull(),
            'owner_id' => $this->integer()->notNull()->comment('references user.id'),

            'listing_type' => $this->string(20)->notNull()->comment('rent, sale'),
            'price' => $this->decimal(14, 2)->notNull(),
            'price_period' => $this->string(20)->null()->comment('monthly, yearly, one_time'),

            'bedrooms' => $this->smallInteger()->null(),
            'is_furnished' => $this->boolean()->notNull()->defaultValue(false),

            'has_parking' => $this->boolean()->notNull()->defaultValue(false),
            'has_water' => $this->boolean()->notNull()->defaultValue(false),
            'has_electricity' => $this->boolean()->notNull()->defaultValue(false),
            'has_security' => $this->boolean()->notNull()->defaultValue(false),
            'has_internet' => $this->boolean()->notNull()->defaultValue(false),

            'status' => $this->string(20)->notNull()->defaultValue('pending')
                ->comment('pending, verified, rejected, reported'),
            'is_available' => $this->boolean()->notNull()->defaultValue(true),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-property-category_id', '{{%property}}', 'category_id', '{{%property_category}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-property-location_id', '{{%property}}', 'location_id', '{{%location}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-property-owner_id', '{{%property}}', 'owner_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('idx-property-status', '{{%property}}', 'status');
        $this->createIndex('idx-property-listing_type', '{{%property}}', 'listing_type');
        $this->createIndex('idx-property-is_available', '{{%property}}', 'is_available');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-property-category_id', '{{%property}}');
        $this->dropForeignKey('fk-property-location_id', '{{%property}}');
        $this->dropForeignKey('fk-property-owner_id', '{{%property}}');
        $this->dropTable('{{%property}}');
    }
}
