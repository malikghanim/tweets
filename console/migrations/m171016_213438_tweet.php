<?php

use yii\db\Migration;

/**
 * Class m171016_213438_tweet
 */
class m171016_213438_tweet extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%tweet}}', [
            'id' => $this->primaryKey(),
            'keyword_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'country_id' => $this->integer(11),
            'country_name' => $this->string(),
            'city_name' => $this->string(),
            'location' => $this->string(),
            'coordinates' => $this->string(),
            'altitude' => $this->string(),
            'longtitude' => $this->string(),
            'description' => $this->string(),
            'tweet_owner' => $this->string(),
            'profile_image' => $this->string(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()
        ]);

        // add foreign key for table `tweet`
        $this->addForeignKey(
            'fk-tweet-user_id',
            'tweet',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        // add foreign key for table `tweet`
        $this->addForeignKey(
            'fk-tweet-keyword_id',
            'tweet',
            'keyword_id',
            'keyword',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-tweet-user_id', 'tweet');
        $this->dropForeignKey('fk-tweet-keyword_id', 'tweet');
        $this->dropTable('{{%tweet}}');
    }
}
