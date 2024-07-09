<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category_post}}`.
 */
class m240702_095621_create_category_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category_post}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'slug' => $this->string(),
            'description' => $this->text(),
            'user_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'deleted_at' => $this->dateTime()
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-category_post-user_id}}',
            '{{%category_post}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-category_post-user_id}}',
            '{{%category_post}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         // drops foreign key for table `{{%users}}`
         $this->dropForeignKey(
            '{{%fk-category_post-user_id}}',
            '{{%category_post}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-category_post-user_id}}',
            '{{%category_post}}'
        );

        $this->dropTable('{{%category_post}}');
    }
}
