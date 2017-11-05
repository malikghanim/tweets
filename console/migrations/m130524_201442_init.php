<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'first_name' => $this->string(),
            'last_name' => $this->string(),
            'role' => $this->integer(),
            'company' => $this->string(),
            'api_key' => $this->string(),
            'api_secret' => $this->string(),
            'facebook_id' => $this->string(),
            'google_id' => $this->string(),
            'is_system_password' => $this->integer(),
            'has_verified_email' => $this->integer(),
            'email_confirm_token' => $this->string(),
            'user_group' => $this->integer(),
            'ip_address' => $this->string(),
            'phone_number' => $this->string(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
