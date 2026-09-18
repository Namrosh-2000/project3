<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%favorite}}`.
 */
class m260907_153000_create_favorite_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%favorite}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'property_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-favorite-user_id', '{{%favorite}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-favorite-property_id', '{{%favorite}}', 'property_id', '{{%property}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx-favorite-user_property', '{{%favorite}}', ['user_id', 'property_id'], true);
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-favorite-user_id', '{{%favorite}}');
        $this->dropForeignKey('fk-favorite-property_id', '{{%favorite}}');
        $this->dropTable('{{%favorite}}');
    }
}
