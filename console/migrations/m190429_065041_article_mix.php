<?php

use yii\db\Migration;

/**
 * Class m190429_065041_article_mix
 */
class m190429_065041_article_mix extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%article_mix}}', [
            'id' => $this->primaryKey(),
            'website_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'longtail_keywords_ids' => $this->text()->notNull(),
            'template_id' => $this->integer(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%article_mix}}');
    }
}
