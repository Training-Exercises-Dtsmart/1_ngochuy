<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%users}}`.
 */
class m240731_083331_add_allowance_and_allowance_updated_at_column_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
            $this->addColumn('{{%users}}', 'allowance', $this->integer()->notNull()->defaultValue(0));
            $this->addColumn('{{%users}}', 'allowance_updated_at', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
            $this->dropColumn('{{%users}}', 'allowance');
            $this->dropColumn('{{%users}}', 'allowance_updated_at');
    }
}
