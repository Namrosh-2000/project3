<?php

use yii\db\Migration;

/**
 * Creates {{%payment}} table – Phase 7D.
 *
 * Relations:
 *   - payment.booking_id  → booking.id
 *   - payment.seeker_id   → user.id
 *   - payment.owner_id    → user.id
 *
 * A booking may have at most ONE non-cancelled payment at a time.
 * The application enforces this via Payment::findActive(); the
 * unique index below prevents two simultaneous active rows per booking.
 */
class m260918_120000_create_payment_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%payment}}', [
            'id'               => $this->primaryKey(),
            'payment_code'     => $this->string(30)->notNull()->unique()
                                       ->comment('Human-readable ref, e.g. PAY-XXXXXX'),

            'booking_id'       => $this->integer()->notNull(),
            'seeker_id'        => $this->integer()->notNull(),
            'owner_id'         => $this->integer()->notNull(),

            // Financial
            'amount'           => $this->decimal(14, 2)->notNull()
                                       ->comment('Amount due in the stated currency'),
            'currency'         => $this->string(3)->notNull()->defaultValue('TZS'),
            'transaction_id'   => $this->string(100)->null()
                                       ->comment('External gateway transaction / reference ID'),
            'payment_method'   => $this->string(30)->null()
                                       ->comment('mpesa, tigopesa, airtel_money, bank_transfer, cash, other'),

            // Status lifecycle: pending → paid | failed | cancelled
            'status'           => $this->string(20)->notNull()->defaultValue('pending')
                                       ->comment('pending, paid, failed, cancelled'),
            'failure_reason'   => $this->text()->null()
                                       ->comment('Description of failure or cancellation reason'),

            'paid_at'          => $this->integer()->null()
                                       ->comment('Unix timestamp when payment confirmed paid'),
            'created_at'       => $this->integer()->notNull(),
            'updated_at'       => $this->integer()->notNull(),
        ]);

        // Foreign keys
        $this->addForeignKey('fk-payment-booking_id', '{{%payment}}', 'booking_id', '{{%booking}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-payment-seeker_id',  '{{%payment}}', 'seeker_id',  '{{%user}}',    'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-payment-owner_id',   '{{%payment}}', 'owner_id',   '{{%user}}',    'id', 'CASCADE', 'CASCADE');

        // Performance & integrity indexes
        $this->createIndex('idx-payment-booking_id', '{{%payment}}', 'booking_id');
        $this->createIndex('idx-payment-seeker_id',  '{{%payment}}', 'seeker_id');
        $this->createIndex('idx-payment-status',     '{{%payment}}', 'status');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-payment-booking_id', '{{%payment}}');
        $this->dropForeignKey('fk-payment-seeker_id',  '{{%payment}}');
        $this->dropForeignKey('fk-payment-owner_id',   '{{%payment}}');
        $this->dropTable('{{%payment}}');
    }
}

