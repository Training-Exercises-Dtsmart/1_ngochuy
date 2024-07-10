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
            'price' => $this->double(),
            'category_product_id' => $this->integer(),
            'slug' => $this->string(),
            'detail' => $this->text(),
            'availabel_stock' => $this->integer()->unsigned(),
            'is_best_sell' => $this->smallInteger(),
            'status' => $this->smallInteger(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
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

        // add foregin key for table `{{%users}}`
        $this->createIndex(
            '{{%idx-products-created_by}}',
            '{{%products}}',
            'created_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-products-created_by}}',
            '{{%products}}',
            'created_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        

        // add foregin key for table `{{%users}}`
        $this->createIndex(
            '{{%idx-products-updated_by}}',
            '{{%products}}',
            'updated_by'
        );

        // add foregin key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-products-updated_by}}',
            '{{%products}}',
            'updated_by',
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

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-products-created_by}}',
            '{{%products}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-products-created_by}}',
            '{{%products}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-products-updated_by}}',
            '{{%products}}'
        );

        $this->dropTable('{{%products}}');
    }
}

