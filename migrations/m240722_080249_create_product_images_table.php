<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product_images}}`.
 */
class m240722_080249_create_product_images_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_images}}', [
            'id' => $this->primaryKey(),
             'image' => $this->string(),
             'product_id' => $this->integer(11)
        ]);

        // create index on product table
         $this->createIndex(
            '{{%idx-product_images-product_id}}',
            '{{%product_images}}',
            'product_id'
        );

         // add foreign key to product table
         $this->addForeignKey(
            '{{%fk-product_images-product_id}}',
            '{{%product_images}}',
            'product_id',
            '{{%products}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         // drop foreign key for table 'products'
         $this->dropForeignKey(
            '{{%fk-product_images-product_id}}',
            '{{%product_images}}'
        );
         // drop index for column 'product_id'
         $this->dropIndex(
            '{{%idx-product_images-product_id}}',
            '{{%product_images}}'
        );

        $this->dropTable('{{%product_images}}');
    }
}
