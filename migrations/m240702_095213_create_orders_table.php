<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%orders}}`.
 */
class m240702_095213_create_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%orders}}', [
            'id' => $this->primaryKey(),
            'quantity' => $this->integer(),
            'total_amount' => $this->float(),
            'order_date' => $this->date(),
            'payment_method' => $this->string(),
            'shipping_address' => $this->string(),
            'status' => $this->boolean(),
            'customer_id' => $this->integer(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
            'deleted_at' => $this->timestamp(),
        ]);

           // creates index for column `customer_id`
           $this->createIndex(
            '{{%idx-orders-customer_id}}',
            '{{%orders}}',
            'customer_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-orders-customer_id}}',
            '{{%orders}}',
            'customer_id',
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
            '{{%fk-orders-customer_id}}',
            '{{%orders}}'
        );

        // drops index for column `customer_id`
        $this->dropIndex(
            '{{%idx-orders-customer_id}}',
            '{{%orders}}'
        );

        $this->dropTable('{{%orders}}');
    }
}
