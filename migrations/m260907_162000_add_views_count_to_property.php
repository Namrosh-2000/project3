<?php

use yii\db\Migration;

/**
 * Adds views_count column to property table.
 */
class m260907_162000_add_views_count_to_property extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%property}}', 'views_count', $this->integer()->notNull()->defaultValue(0));
        $this->createIndex('idx-property-views_count', '{{%property}}', 'views_count');
    }

    public function safeDown()
    {
        $this->dropColumn('{{%property}}', 'views_count');
    }
}