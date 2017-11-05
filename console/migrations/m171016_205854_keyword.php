<?php

use yii\db\Migration;

/**
 * Class m171016_205854_keyword
 */
class m171016_205854_keyword extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%keyword}}', [
            'id' => $this->primaryKey(),
            'keyword' => $this->string()->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()
        ]);

        // add foreign key for table `keyword`
        $this->addForeignKey(
            'fk-keyword-user_id',
            'keyword',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-keyword-user_id', 'keyword');
        $this->dropTable('{{%keyword}}');
    }
}
