<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%market_indicator}}`.
 *
 * Phase 4 (Local data architecture): admin-managed local-market
 * signals — one row per (ward [+ optional category]) per indicator
 * type. This is the real data MarketAnalysisService layers on top of
 * the existing listing-based match score (§30 of the spec:
 * "Competition / Opportunity Data").
 *
 * `indicator_type` intentionally reuses the seven areas already named
 * in the Fursa preview copy from Phase 1 (`fursa.item_*` message
 * keys) — demand, competition, market_strength, gaps, fit, risk,
 * density — so a row written here maps directly onto an existing,
 * already-translated label instead of introducing a second taxonomy.
 *
 * `category_id` is nullable: a row with no category applies to the
 * whole ward (e.g. a population estimate); a row with a category
 * narrows to that specific business type (e.g. competition level for
 * "Saluni" specifically).
 *
 * No numeric score here is ever invented — `value_numeric` /
 * `value_label` are only ever what an admin has actually entered, and
 * `confidence_level` + `data_source_id` travel with it everywhere it
 * is displayed so the UI never shows a bare number.
 */
class m260918_091000_create_market_indicator_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%market_indicator}}', [
            'id' => $this->primaryKey(),
            'location_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->null()->comment('null = applies to the whole ward, not one business category'),
            'data_source_id' => $this->integer()->notNull(),

            'indicator_type' => $this->string(30)->notNull()
                ->comment('demand, competition, market_strength, gaps, fit, risk, density'),
            'value_numeric' => $this->decimal(12, 2)->null()->comment('optional quantitative reading, e.g. estimated resident count'),
            'value_label' => $this->string(100)->null()->comment('optional qualitative reading, e.g. "Juu", "Wastani", "Chini"'),
            'confidence_level' => $this->string(10)->notNull()->defaultValue('low')
                ->comment('low, medium, high — how much this reading should be trusted'),
            'notes' => $this->text()->null(),

            'is_demo' => $this->boolean()->notNull()->defaultValue(true)
                ->comment('true = placeholder/sample data; must render with the Demo Data badge'),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-market_indicator-location_id', '{{%market_indicator}}', 'location_id', '{{%location}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-market_indicator-category_id', '{{%market_indicator}}', 'category_id', '{{%property_category}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk-market_indicator-data_source_id', '{{%market_indicator}}', 'data_source_id', '{{%local_data_source}}', 'id', 'RESTRICT', 'CASCADE');

        $this->createIndex('idx-market_indicator-location_id', '{{%market_indicator}}', 'location_id');
        $this->createIndex('idx-market_indicator-category_id', '{{%market_indicator}}', 'category_id');
        $this->createIndex('idx-market_indicator-indicator_type', '{{%market_indicator}}', 'indicator_type');
        // One admin-entered reading per (ward, category, indicator type) — editing updates it in place rather than piling up duplicates.
        $this->createIndex('idx-market_indicator-unique_reading', '{{%market_indicator}}', ['location_id', 'category_id', 'indicator_type'], true);
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-market_indicator-location_id', '{{%market_indicator}}');
        $this->dropForeignKey('fk-market_indicator-category_id', '{{%market_indicator}}');
        $this->dropForeignKey('fk-market_indicator-data_source_id', '{{%market_indicator}}');
        $this->dropTable('{{%market_indicator}}');
    }
}
