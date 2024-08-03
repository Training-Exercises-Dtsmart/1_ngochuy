<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%posts}}`.
 */
class m240803_053525_drop_position_column_from_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->dropColumn('{{%posts}}', 'position');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
            $this->addColumn('{{%posts}}', 'position', $this->integer(11));
    }
}
