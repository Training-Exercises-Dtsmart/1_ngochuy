<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%random}}`.
 */
class m240802_100111_create_random_table extends Migration
{
     /**
      * {@inheritdoc}
      */
     public function safeUp()
     {
          $this->createTable('{{%random}}', [
               'id' => $this->primaryKey(),
               'name' => $this->string(),
               'deleted_at' => $this->integer(11),
               'created_at' => $this->integer(11),
               'updated_at' => $this->integer(11),
          ]);
     }

     /**
      * {@inheritdoc}
      */
     public function safeDown()
     {
          $this->dropTable('{{%random}}');
     }
}
