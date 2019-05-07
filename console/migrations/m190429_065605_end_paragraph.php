<?php

use yii\db\Migration;

/**
 * Class m190429_065605_end_paragraph
 */
class m190429_065605_end_paragraph extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%end_paragraph}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'content' => $this->text()->notNull(),
            'times' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%end_paragraph}}');
    }
}
