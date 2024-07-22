<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%video_gallery}}`.
 */
class m240722_080643_create_video_gallery_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%video_gallery}}', [
            'id' => $this->primaryKey(),
             'name' => $this->string(),
             'description' => $this->string(),
             'url' => $this->string(),
             'meta_keywords' => $this->string(255),
             'meta_description' => $this->string(255),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%video_gallery}}');
    }
}
