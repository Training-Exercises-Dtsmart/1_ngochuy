<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%country}}`.
 */
class m240729_094256_create_country_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%country}}', [
            'code' => $this->string(2),
            'name' => $this->string(52),
            'population' => $this->integer(11)->defaultValue(0),
        ]);

        $this->addPrimaryKey('code', '{{%country}}', 'code');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%country}}');
    }
}
