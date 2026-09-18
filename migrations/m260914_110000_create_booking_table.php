<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%booking}}`.
 */
class m260914_110000_create_booking_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%booking}}', [
            'id' => $this->primaryKey(),
            'booking_code' => $this->string(20)->notNull()->unique(),
            'property_id' => $this->integer()->notNull(),
            'seeker_id' => $this->integer()->notNull(),
            'owner_id' => $this->integer()->notNull(),

            'booking_type' => $this->string(20)->notNull()->defaultValue('visit')
                ->comment('visit, reservation'),
            'booking_date' => $this->date()->notNull(),
            'booking_time' => $this->string(20)->notNull(),
            'seeker_phone' => $this->string(20)->null(),
            'notes' => $this->text()->null(),

            'status' => $this->string(20)->notNull()->defaultValue('pending')
                ->comment('pending, confirmed, rescheduled, rejected, completed, cancelled'),
            'owner_response_notes' => $this->text()->null(),
            'proposed_date' => $this->date()->null(),
            'proposed_time' => $this->string(20)->null(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-booking-property_id', '{{%booking}}', 'property_id', '{{%property}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-booking-seeker_id', '{{%booking}}', 'seeker_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-booking-owner_id', '{{%booking}}', 'owner_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('idx-booking-status', '{{%booking}}', 'status');
        $this->createIndex('idx-booking-seeker_id', '{{%booking}}', 'seeker_id');
        $this->createIndex('idx-booking-owner_id', '{{%booking}}', 'owner_id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-booking-property_id', '{{%booking}}');
        $this->dropForeignKey('fk-booking-seeker_id', '{{%booking}}');
        $this->dropForeignKey('fk-booking-owner_id', '{{%booking}}');
        $this->dropTable('{{%booking}}');
    }
}
