<?php

use yii\db\Migration;

/**
 * Adds reply and phone fields to inquiry table.
 */
class m260907_163000_add_reply_to_inquiry extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%inquiry}}', 'reply', $this->text()->null()->after('message'));
        $this->addColumn('{{%inquiry}}', 'phone', $this->string(20)->null()->after('message'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%inquiry}}', 'reply');
        $this->dropColumn('{{%inquiry}}', 'phone');
    }
}