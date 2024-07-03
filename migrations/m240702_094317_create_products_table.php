<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%products}}`.
 */
class m240702_094317_create_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'description' => $this->text(),
            'price' => $this->float(),
            'category_product_id' => $this->integer(),
            'slug' => $this->string(),
            'details' => $this->text(),
            'quantity' => $this->integer(),
            'is_best_sell' => $this->boolean(),
            'product_status' => $this->boolean(),
            'user_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'deleted_at' => $this->dateTime(),
        ]);

         // creates index for column `category_product_id`
         $this->createIndex(
            '{{%idx-products-category_product_id}}',
            '{{%products}}',
            'category_product_id'
        );


        // add foreign key for table `{{%category_product}}`
        $this->addForeignKey(
            '{{%fk-products-category_product_id}}',
            '{{%products}}',
            'category_product_id',
            '{{%category_product}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         // drops foreign key for table `{{%category_product}}`
         $this->dropForeignKey(
            '{{%fk-products-category_product_id}}',
            '{{%products}}'
        );

        // drops index for column `category_product_id`
        $this->dropIndex(
            '{{%idx-products-category_product_id}}',
            '{{%products}}'
        );

        $this->dropTable('{{%products}}');
    }
}

