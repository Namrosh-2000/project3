<?php

use yii\db\Migration;

/**
 * Handles adding preferred location and available capital to table `{{%user}}`.
 * Part of the "Onyesho la Mapendekezo" (recommendation wizard) on the seeker profile.
 */
class m260915_090000_add_location_capital_to_user_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'preferred_location', $this->string(150)->null()->comment('Eneo analopendelea kuanzisha biashara, e.g. Kariakoo, Dar es Salaam'));
        $this->addColumn('{{%user}}', 'capital_budget', $this->bigInteger()->null()->comment('Mtaji alionao kwa TZS'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'capital_budget');
        $this->dropColumn('{{%user}}', 'preferred_location');
    }
}
