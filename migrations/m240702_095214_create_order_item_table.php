<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_item}}`.
 */
class m240702_095214_create_order_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order_item}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(),
            'product_id' => $this->integer(),
            'quantity' => $this->integer(),
            'price' => $this->double(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'deleted_at' => $this->dateTime(), 
        ]);

          // creates index for column `order_id`
          $this->createIndex(
            '{{%idx-order_item-order_id}}',
            '{{%order_item}}',
            'order_id'
        );

        // add foreign key for table `{{%orders}}`
        $this->addForeignKey(
            '{{%fk-order_item-order_id}}',
            '{{%order_item}}',
            'order_id',
            '{{%orders}}',
            'id',
            'CASCADE'
        );

        // creates index for column `product_id`
        $this->createIndex(
            '{{%idx-order_item-product_id}}',
            '{{%order_item}}',
            'product_id'
        );

        // add foreign key for table `{{%products}}`
        $this->addForeignKey(
            '{{%fk-order_item-product_id}}',
            '{{%order_item}}',
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
           // drops foreign key for table `{{%orders}}`
           $this->dropForeignKey(
            '{{%fk-order_item-order_id}}',
            '{{%order_item}}'
        );

        // drops index for column `order_id`
        $this->dropIndex(
            '{{%idx-order_item-order_id}}',
            '{{%order_item}}'
        );

        // drops foreign key for table `{{%products}}`
        $this->dropForeignKey(
            '{{%fk-order_item-product_id}}',
            '{{%order_item}}'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            '{{%idx-order_item-product_id}}',
            '{{%order_item}}'
        );

        $this->dropTable('{{%order_item}}');
    }
}