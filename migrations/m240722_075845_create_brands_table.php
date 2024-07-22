<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%brands}}`.
 */
class m240722_075845_create_brands_table extends Migration
{
     /**
      * {@inheritdoc}
      */
     public function safeUp()
     {
          $this->createTable('{{%brands}}', [
               'id' => $this->primaryKey(),
               'name' => $this->string(255),
               'status' => $this->smallInteger()->defaultValue(1)
          ]);
     }

     /**
      * {@inheritdoc}
      */
     public function safeDown()
     {
          $this->dropTable('{{%brands}}');
     }
}
