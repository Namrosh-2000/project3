<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%location}}`.
 */
class m260907_132246_create_location_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%location}}', [
            'id' => $this->primaryKey(),
            'region' => $this->string(100)->notNull()->defaultValue('Dar es Salaam'),
            'municipality' => $this->string(100)->notNull()->defaultValue('Kinondoni'),
            'ward' => $this->string(100)->notNull(),
            'street' => $this->string(150)->null(),
            'latitude' => $this->decimal(10, 7)->null(),
            'longitude' => $this->decimal(10, 7)->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-location-ward', '{{%location}}', 'ward');
    }

    public function safeDown()
    {
        $this->dropTable('{{%location}}');
    }
}