<?php

use yii\db\Migration;

/**
 * Handles adding business interests, experience level, and preferred amenities to table `{{%user}}`.
 */
class m260914_140000_add_business_interests_to_user_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'business_interests', $this->text()->null()->comment('Comma-separated list or JSON of preferred business categories'));
        $this->addColumn('{{%user}}', 'experience_level', $this->string(20)->notNull()->defaultValue('beginner')->comment('beginner, intermediate, expert'));
        $this->addColumn('{{%user}}', 'preferred_amenities', $this->string(255)->null()->comment('Comma-separated preferred amenities e.g. water,electricity,parking'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'preferred_amenities');
        $this->dropColumn('{{%user}}', 'experience_level');
        $this->dropColumn('{{%user}}', 'business_interests');
    }
}
