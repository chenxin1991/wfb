<?php

use yii\db\Migration;

/**
 * Class m190415_153610_website
 */
class m190415_153610_website extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%website}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull()->unique(),
            'platform_id' => $this->integer()->notNull(),
            'industry_id' => $this->integer()->notNull(),
            'site_id' => $this->integer()->notNull()->unique(),
            'api_key' => $this->string(255)->notNull()->unique(),
            'product_ids' => $this->string(255),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%website}}');
    }
}
