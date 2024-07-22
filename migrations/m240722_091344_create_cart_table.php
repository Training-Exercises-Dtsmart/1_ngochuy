<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cart}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 * - `{{%products}}`
 * - `{{%deliver_types}}`
 */
class m240722_091344_create_cart_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cart}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'product_id' => $this->integer(),
            'qty' => $this->integer()->defaultValue(1),
            'delivery_types_id' => $this->integer(),
            'created_at' => $this->integer(),
            'status' => $this->smallInteger(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-cart-user_id}}',
            '{{%cart}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-cart-user_id}}',
            '{{%cart}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `product_id`
        $this->createIndex(
            '{{%idx-cart-product_id}}',
            '{{%cart}}',
            'product_id'
        );

        // add foreign key for table `{{%products}}`
        $this->addForeignKey(
            '{{%fk-cart-product_id}}',
            '{{%cart}}',
            'product_id',
            '{{%products}}',
            'id',
            'CASCADE'
        );

        // creates index for column `delivery_types_id`
        $this->createIndex(
            '{{%idx-cart-delivery_types_id}}',
            '{{%cart}}',
            'delivery_types_id'
        );

        // add foreign key for table `{{%deliver_types}}`
        $this->addForeignKey(
            '{{%fk-cart-delivery_types_id}}',
            '{{%cart}}',
            'delivery_types_id',
            '{{%delivery_types}}',
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
            '{{%fk-cart-user_id}}',
            '{{%cart}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-cart-user_id}}',
            '{{%cart}}'
        );

        // drops foreign key for table `{{%products}}`
        $this->dropForeignKey(
            '{{%fk-cart-product_id}}',
            '{{%cart}}'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            '{{%idx-cart-product_id}}',
            '{{%cart}}'
        );

        // drops foreign key for table `{{%deliver_types}}`
        $this->dropForeignKey(
            '{{%fk-cart-delivery_types_id}}',
            '{{%cart}}'
        );

        // drops index for column `delivery_types_id`
        $this->dropIndex(
            '{{%idx-cart-delivery_types_id}}',
            '{{%cart}}'
        );

        $this->dropTable('{{%cart}}');
    }
}
