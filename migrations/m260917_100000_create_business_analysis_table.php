<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%business_analysis}}`.
 *
 * Phase 3 (Fursa / business analysis interface): stores each market
 * analysis an entrepreneur runs so it can be listed under
 * "Historia ya Uchambuzi" and reopened later. `results_snapshot`
 * freezes the computed BusinessOpportunityService output at the time
 * of the request, so a reopened analysis still shows exactly what the
 * user saw even if listings/prices have changed since.
 */
class m260917_100000_create_business_analysis_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%business_analysis}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->comment('references user.id (entrepreneur who ran the analysis)'),

            'business_type' => $this->string(150)->notNull()->comment('Aina ya biashara'),
            'business_vision' => $this->text()->null()->comment('Maono ya biashara'),
            'target_customers' => $this->string(255)->null()->comment('Wateja unaowalenga'),

            'ward' => $this->string(100)->notNull()->comment('Eneo unalolenga'),
            'location_id' => $this->integer()->null(),

            'starting_budget' => $this->bigInteger()->null()->comment('Bajeti ya kuanzia (mtaji)'),
            'rental_budget' => $this->bigInteger()->notNull()->comment('Bajeti ya kodi kwa mwezi'),
            'space_size_needed' => $this->string(100)->null()->comment('Ukubwa wa eneo unaohitaji'),
            'special_requirements' => $this->text()->null()->comment('Mahitaji maalum'),

            'status' => $this->string(20)->notNull()->defaultValue('completed')
                ->comment('completed — reserved for future async/processing states'),
            'results_snapshot' => $this->text()->null()->comment('JSON snapshot of the computed results shown to the user'),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-business_analysis-user_id', '{{%business_analysis}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-business_analysis-location_id', '{{%business_analysis}}', 'location_id', '{{%location}}', 'id', 'SET NULL', 'CASCADE');

        $this->createIndex('idx-business_analysis-user_id', '{{%business_analysis}}', 'user_id');
        $this->createIndex('idx-business_analysis-created_at', '{{%business_analysis}}', 'created_at');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-business_analysis-user_id', '{{%business_analysis}}');
        $this->dropForeignKey('fk-business_analysis-location_id', '{{%business_analysis}}');
        $this->dropTable('{{%business_analysis}}');
    }
}
