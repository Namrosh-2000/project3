<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%property_image}}`.
 */
class m260907_152000_create_property_image_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%property_image}}', [
            'id' => $this->primaryKey(),
            'property_id' => $this->integer()->notNull(),
            'image_path' => $this->string(255)->notNull(),
            'is_cover' => $this->boolean()->notNull()->defaultValue(false),
            'sort_order' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-property_image-property_id',
            '{{%property_image}}',
            'property_id',
            '{{%property}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex('idx-property_image-property_id', '{{%property_image}}', 'property_id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-property_image-property_id', '{{%property_image}}');
        $this->dropTable('{{%property_image}}');
    }
}
