<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%property_category}}`.
 */
class m260907_135237_create_property_category_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%property_category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'slug' => $this->string(120)->notNull(),
            'type' => $this->string(30)->notNull()->comment('room, apartment, house, business_space'),
            'description' => $this->text()->null(),
            'is_active' => $this->boolean()->notNull()->defaultValue(true),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-property_category-slug', '{{%property_category}}', 'slug', true);
        $this->createIndex('idx-property_category-type', '{{%property_category}}', 'type');
    }

    public function safeDown()
    {
        $this->dropTable('{{%property_category}}');
    }
}