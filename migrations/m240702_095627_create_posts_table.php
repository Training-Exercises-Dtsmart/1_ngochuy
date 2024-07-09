<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%posts}}`.
 */
class m240702_095627_create_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%posts}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'user_id' => $this->integer(),
            'category_id' => $this->integer(),
            'content' => $this->text(),
            'status' => $this->smallInteger(),
            'image' => $this->string(255),
            'thumbnail' => $this->string(255),
            'short_description' => $this->string(),
            'slug' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'deleted_at' => $this->dateTime(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-posts-user_id}}',
            '{{%posts}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-posts-user_id}}',
            '{{%posts}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-posts-category_id}}',
            '{{%posts}}',
            'category_id'
        );

        // add foreign key for table `{{%category_post}}`
        $this->addForeignKey(
            '{{%fk-posts-category_id}}',
            '{{%posts}}',
            'category_id',
            '{{%category_post}}',
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
            '{{%fk-posts-user_id}}',
            '{{%posts}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-posts-user_id}}',
            '{{%posts}}'
        );

        // drops foreign key for table `{{%category_post}}`
        $this->dropForeignKey(
            '{{%fk-posts-category_id}}',
            '{{%posts}}'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            '{{%idx-posts-category_id}}',
            '{{%posts}}'
        );

        $this->dropTable('{{%posts}}');
    }
}
