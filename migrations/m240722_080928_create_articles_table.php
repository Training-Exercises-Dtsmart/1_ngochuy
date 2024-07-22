<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%articles}}`.
 */
class m240722_080928_create_articles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%articles}}', [
            'id' => $this->primaryKey(),
             'title' => $this->string(),
             'content' => $this->text(),
             'key' => $this->string(),
             'created_at' => $this->integer(),
             'updated_at' => $this->integer(),
             'meta_keywords' => $this->string(255),
             'meta_description' => $this->string(255)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%articles}}');
    }
}
