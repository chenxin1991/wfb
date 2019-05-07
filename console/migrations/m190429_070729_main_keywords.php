<?php

use yii\db\Migration;

/**
 * Class m190429_070729_main_keywords
 */
class m190429_070729_main_keywords extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%main_keywords}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'website_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%main_keywords}}');
    }
}
