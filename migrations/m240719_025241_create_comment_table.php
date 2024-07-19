<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comment}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%products}}`
 * - `{{%comment}}`
 * - `{{%users}}`
 */
class m240719_025241_create_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'comment' => $this->text(),
            'product_id' => $this->integer(11),
            'parent_id' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->integer(11),
        ]);

        // creates index for column `product_id`
        $this->createIndex(
            '{{%idx-comment-product_id}}',
            '{{%comment}}',
            'product_id'
        );

        // add foreign key for table `{{%products}}`
        $this->addForeignKey(
            '{{%fk-comment-product_id}}',
            '{{%comment}}',
            'product_id',
            '{{%products}}',
            'id',
            'CASCADE'
        );

        // creates index for column `parent_id`
        $this->createIndex(
            '{{%idx-comment-parent_id}}',
            '{{%comment}}',
            'parent_id'
        );

        // add foreign key for table `{{%comment}}`
        $this->addForeignKey(
            '{{%fk-comment-parent_id}}',
            '{{%comment}}',
            'parent_id',
            '{{%comment}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-comment-created_by}}',
            '{{%comment}}',
            'created_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-comment-created_by}}',
            '{{%comment}}',
            'created_by',
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
        // drops foreign key for table `{{%products}}`
        $this->dropForeignKey(
            '{{%fk-comment-product_id}}',
            '{{%comment}}'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            '{{%idx-comment-product_id}}',
            '{{%comment}}'
        );

        // drops foreign key for table `{{%comment}}`
        $this->dropForeignKey(
            '{{%fk-comment-parent_id}}',
            '{{%comment}}'
        );

        // drops index for column `parent_id`
        $this->dropIndex(
            '{{%idx-comment-parent_id}}',
            '{{%comment}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-comment-created_by}}',
            '{{%comment}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-comment-created_by}}',
            '{{%comment}}'
        );

        $this->dropTable('{{%comment}}');
    }
}
