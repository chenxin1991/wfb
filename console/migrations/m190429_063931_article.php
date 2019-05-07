<?php

use yii\db\Migration;

/**
 * Class m190429_063931_article
 */
class m190429_063931_article extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%article}}', [
            'id' => $this->primaryKey(),
            'source_id' => $this->integer(),
            'website_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'title' => $this->string(100)->notNull(),
            'seo_title' => $this->string(100)->notNull(),
            'keywords' => $this->string(50)->notNull(),
            'description' => $this->string(255)->notNull(),
            'content' => $this->text()->notNull(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%article}}');
    }
}
