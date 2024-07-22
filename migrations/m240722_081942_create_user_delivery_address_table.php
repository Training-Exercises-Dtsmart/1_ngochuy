<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_delivery_address}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%places}}`
 * - `{{%users}}`
 */
class m240722_081942_create_user_delivery_address_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_delivery_address}}', [
            'id' => $this->primaryKey(),
            'place_id' => $this->integer(11),
            'user_id' => $this->integer(11),
            'address' => $this->string(255),
            'lat' => $this->float(),
            'lng' => $this->float(),
            'phone' => $this->string(55),
        ]);

        // creates index for column `place_id`
        $this->createIndex(
            '{{%idx-user_delivery_address-place_id}}',
            '{{%user_delivery_address}}',
            'place_id'
        );

        // add foreign key for table `{{%places}}`
        $this->addForeignKey(
            '{{%fk-user_delivery_address-place_id}}',
            '{{%user_delivery_address}}',
            'place_id',
            '{{%places}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_delivery_address-user_id}}',
            '{{%user_delivery_address}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-user_delivery_address-user_id}}',
            '{{%user_delivery_address}}',
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
        // drops foreign key for table `{{%places}}`
        $this->dropForeignKey(
            '{{%fk-user_delivery_address-place_id}}',
            '{{%user_delivery_address}}'
        );

        // drops index for column `place_id`
        $this->dropIndex(
            '{{%idx-user_delivery_address-place_id}}',
            '{{%user_delivery_address}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-user_delivery_address-user_id}}',
            '{{%user_delivery_address}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_delivery_address-user_id}}',
            '{{%user_delivery_address}}'
        );

        $this->dropTable('{{%user_delivery_address}}');
    }
}
