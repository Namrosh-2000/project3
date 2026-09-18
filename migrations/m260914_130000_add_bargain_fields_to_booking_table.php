<?php

use yii\db\Migration;

/**
 * Handles adding bargain price columns to table `{{%booking}}`.
 */
class m260914_130000_add_bargain_fields_to_booking_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%booking}}', 'offered_price', $this->decimal(12, 2)->null()->comment('Seeker bargain price offer'));
        $this->addColumn('{{%booking}}', 'agreed_price', $this->decimal(12, 2)->null()->comment('Final accepted price for contract'));
        $this->addColumn('{{%booking}}', 'bargain_status', $this->string(20)->notNull()->defaultValue('none')->comment('none, pending, accepted, rejected'));

        $this->createIndex('idx-booking-bargain_status', '{{%booking}}', 'bargain_status');
    }

    public function safeDown()
    {
        $this->dropIndex('idx-booking-bargain_status', '{{%booking}}');
        $this->dropColumn('{{%booking}}', 'bargain_status');
        $this->dropColumn('{{%booking}}', 'agreed_price');
        $this->dropColumn('{{%booking}}', 'offered_price');
    }
}
