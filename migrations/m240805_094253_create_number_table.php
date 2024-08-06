<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%number}}`.
 */
class m240805_094253_create_number_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%number}}', [
            'id' => $this->primaryKey(),
            'a' => $this->integer(255),
            'b' => $this->integer(255),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%number}}');
    }
}
