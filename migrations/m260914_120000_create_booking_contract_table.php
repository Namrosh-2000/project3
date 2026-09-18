<?php

use yii\db\Migration;

/**
 * Handles adding contract columns to property table and creating booking_contract table.
 */
class m260914_120000_create_booking_contract_table extends Migration
{
    public function safeUp()
    {
        // Add contract terms & file columns to property table
        $this->addColumn('{{%property}}', 'contract_terms', $this->text()->null()->comment('Owner lease terms and rules'));
        $this->addColumn('{{%property}}', 'contract_file', $this->string(255)->null()->comment('Owner contract document upload path'));

        // Create booking_contract table
        $this->createTable('{{%booking_contract}}', [
            'id' => $this->primaryKey(),
            'contract_code' => $this->string(25)->notNull()->unique(),
            'booking_id' => $this->integer()->notNull(),
            'property_id' => $this->integer()->notNull(),
            'seeker_id' => $this->integer()->notNull(),
            'owner_id' => $this->integer()->notNull(),

            'contract_terms' => $this->text()->null(),
            'status' => $this->string(20)->notNull()->defaultValue('pending_signature')
                ->comment('pending_signature, signed_by_seeker, signed_by_owner, completed, cancelled'),

            'seeker_signature' => $this->string(255)->null(),
            'seeker_signed_at' => $this->integer()->null(),

            'owner_signature' => $this->string(255)->null(),
            'owner_signed_at' => $this->integer()->null(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-booking_contract-booking_id', '{{%booking_contract}}', 'booking_id', '{{%booking}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-booking_contract-property_id', '{{%booking_contract}}', 'property_id', '{{%property}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-booking_contract-seeker_id', '{{%booking_contract}}', 'seeker_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-booking_contract-owner_id', '{{%booking_contract}}', 'owner_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('idx-booking_contract-status', '{{%booking_contract}}', 'status');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-booking_contract-booking_id', '{{%booking_contract}}');
        $this->dropForeignKey('fk-booking_contract-property_id', '{{%booking_contract}}');
        $this->dropForeignKey('fk-booking_contract-seeker_id', '{{%booking_contract}}');
        $this->dropForeignKey('fk-booking_contract-owner_id', '{{%booking_contract}}');
        $this->dropTable('{{%booking_contract}}');

        $this->dropColumn('{{%property}}', 'contract_terms');
        $this->dropColumn('{{%property}}', 'contract_file');
    }
}
