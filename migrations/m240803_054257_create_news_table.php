<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%news}}`.
 */
class m240803_054257_create_news_table extends Migration
{
     /**
      * {@inheritdoc}
      */
     public function safeUp()
     {
          $this->createTable('{{%news}}', [
               'id' => $this->primaryKey(),
               'title' => $this->string()->notNull(),
               'content' => $this->text(),
          ]);
          $this->insert('news', [
               'title' => 'First news',
               'content' => 'First news content',
          ]);
     }

     /**
      * {@inheritdoc}
      */
     public function safeDown()
     {
          $this->delete('news', ['id' => '1']);
          $this->dropTable('{{%news}}');
     }
}
