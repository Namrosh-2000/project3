<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%local_data_source}}`.
 *
 * Phase 4 (Local data architecture): a registry of WHERE each piece of
 * local-market context shown in Fursa actually comes from — a manually
 * collected survey, a verified competition count, a population
 * estimate, etc. Every `market_indicator` row must point to one of
 * these, so the UI can always say "according to X" instead of
 * presenting a number with no origin.
 *
 * `is_demo` is the enforcement point for the product's data-integrity
 * rule (§35 of the spec): a source flagged demo can only ever produce
 * indicators that render behind a "Data ya Mfano" badge, never as if
 * they were verified local intelligence.
 */
class m260918_090000_create_local_data_source_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%local_data_source}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(150)->notNull()->comment('e.g. "Uchunguzi wa Mtaani — Sinza, Aug 2026"'),
            'type' => $this->string(30)->notNull()
                ->comment('manual_observation, population_estimate, economic_indicator, competition_survey, other'),
            'description' => $this->text()->null(),
            'is_demo' => $this->boolean()->notNull()->defaultValue(true)
                ->comment('true = placeholder/sample data, must render with the Demo Data badge and never as verified fact'),
            'collected_at' => $this->date()->null()->comment('when this data was actually gathered, if known'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-local_data_source-type', '{{%local_data_source}}', 'type');
        $this->createIndex('idx-local_data_source-is_demo', '{{%local_data_source}}', 'is_demo');
    }

    public function safeDown()
    {
        $this->dropTable('{{%local_data_source}}');
    }
}
