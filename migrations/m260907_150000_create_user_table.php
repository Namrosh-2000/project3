<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 * Yii2 basic template has no user table; this is required before property.owner_id FK.
 */
class m260907_150000_create_user_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(50)->notNull(),
            'email' => $this->string(255)->notNull(),
            'phone' => $this->string(20)->null(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string(255)->notNull(),
            'password_reset_token' => $this->string(255)->null(),
            'role' => $this->string(20)->notNull()->defaultValue('seeker')
                ->comment('seeker, owner, agent, admin'),
            'status' => $this->smallInteger()->notNull()->defaultValue(10)
                ->comment('0=deleted, 9=inactive, 10=active'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-user-username', '{{%user}}', 'username', true);
        $this->createIndex('idx-user-email', '{{%user}}', 'email', true);
        $this->createIndex('idx-user-role', '{{%user}}', 'role');
        $this->createIndex('idx-user-password_reset_token', '{{%user}}', 'password_reset_token', true);

        $now = time();
        $this->insert('{{%user}}', [
            'username' => 'admin',
            'email' => 'admin@eneolink.local',
            'phone' => null,
            'auth_key' => \Yii::$app->security->generateRandomString(),
            'password_hash' => \Yii::$app->security->generatePasswordHash('admin123'),
            'password_reset_token' => null,
            'role' => 'admin',
            'status' => 10,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
