<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payment_types}}`.
 */
class m240725_083409_create_payment_types_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payment_types}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'class' => $this->string(255),
            'params' => $this->string(255),
            'logo' => $this->string(255),
            'commission' => $this->integer(),
            'active' => $this->integer(),
            'payment_available' => $this->integer(),
            'sort' => $this->integer(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%payment_types}}');
    }
}
