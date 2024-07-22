<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%paycom_transactions}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%orders}}`
 */
class m240722_092139_create_paycom_transactions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%paycom_transactions}}', [
            'id' => $this->primaryKey(),
            'paycom_transaction_id' => $this->string(25),
            'paycom_time' => $this->string(13),
            'paycom_time_datetime' => $this->dateTime(),
            'create_time' => $this->dateTime(),
            'perform_time' => $this->dateTime(),
            'cancel_time' => $this->dateTime(),
            'amount' => $this->integer(),
            'state' => $this->smallInteger(2),
            'reason' => $this->smallInteger(2),
            'order_id' => $this->integer(),
        ]);

        // creates index for column `order_id`
        $this->createIndex(
            '{{%idx-paycom_transactions-order_id}}',
            '{{%paycom_transactions}}',
            'order_id'
        );

        // add foreign key for table `{{%orders}}`
        $this->addForeignKey(
            '{{%fk-paycom_transactions-order_id}}',
            '{{%paycom_transactions}}',
            'order_id',
            '{{%orders}}',
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
            '{{%fk-paycom_transactions-order_id}}',
            '{{%paycom_transactions}}'
        );

        // drops index for column `order_id`
        $this->dropIndex(
            '{{%idx-paycom_transactions-order_id}}',
            '{{%paycom_transactions}}'
        );

        $this->dropTable('{{%paycom_transactions}}');
    }
}
