<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%report}}`.
 */
class m260907_155000_create_report_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%report}}', [
            'id' => $this->primaryKey(),
            'property_id' => $this->integer()->notNull(),
            'reporter_id' => $this->integer()->notNull(),
            'reason' => $this->string(50)->notNull()
                ->comment('fake, duplicate, misleading, other'),
            'details' => $this->text()->null(),
            'status' => $this->string(20)->notNull()->defaultValue('pending')
                ->comment('pending, reviewed, resolved, dismissed'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-report-property_id', '{{%report}}', 'property_id', '{{%property}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-report-reporter_id', '{{%report}}', 'reporter_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx-report-status', '{{%report}}', 'status');
        $this->createIndex('idx-report-property_id', '{{%report}}', 'property_id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-report-property_id', '{{%report}}');
        $this->dropForeignKey('fk-report-reporter_id', '{{%report}}');
        $this->dropTable('{{%report}}');
    }
}
