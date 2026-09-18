<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%inquiry}}`.
 */
class m260907_154000_create_inquiry_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%inquiry}}', [
            'id' => $this->primaryKey(),
            'property_id' => $this->integer()->notNull(),
            'seeker_id' => $this->integer()->notNull(),
            'owner_id' => $this->integer()->notNull(),
            'message' => $this->text()->notNull(),
            'status' => $this->string(20)->notNull()->defaultValue('new')
                ->comment('new, read, replied'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-inquiry-property_id', '{{%inquiry}}', 'property_id', '{{%property}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-inquiry-seeker_id', '{{%inquiry}}', 'seeker_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-inquiry-owner_id', '{{%inquiry}}', 'owner_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx-inquiry-status', '{{%inquiry}}', 'status');
        $this->createIndex('idx-inquiry-owner_id', '{{%inquiry}}', 'owner_id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-inquiry-property_id', '{{%inquiry}}');
        $this->dropForeignKey('fk-inquiry-seeker_id', '{{%inquiry}}');
        $this->dropForeignKey('fk-inquiry-owner_id', '{{%inquiry}}');
        $this->dropTable('{{%inquiry}}');
    }
}
