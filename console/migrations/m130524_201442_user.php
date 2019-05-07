<?php

use yii\db\Migration;

class m130524_201442_user extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->unique(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->insert('{{%user}}', [
            'id' => 1,
            'username' => 'admin',
            'auth_key' => 'TZa3IZRQ5BR377hCi7NNZ59Fk1gU3MQw',
            'password_hash' => '$2y$13$WtT1lZSVo1iKFoIlkG4xI.byas408hSqMP5Jmu3hoAoa4gYdR/4pu',
            'password_reset_token' => null,
            'email' => null,
            'status' => 0,
            'created_at' => 1555245591,
            'updated_at' => 1555338870,
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
