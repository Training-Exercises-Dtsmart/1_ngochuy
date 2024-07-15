<?php

namespace app\modules\models;

use Yii;
use yii\base\BaseObject;
use app\models\Post;

class AddToPost extends BaseObject implements \yii\queue\JobInterface
{
     public $title;
     public $content;

     public function execute($queue)
     {
          Yii::info("Executing AddToPost job", __METHOD__);
         $faker = \Faker\Factory::create();
         for($i = 0; $i < 10; $i++) {
              $post = new Post();
             $post->title = $faker->words(random_int(10,20), true);
             $post->content = $faker->paragraph(random_int(5,20));
             $post->save();
//              if (!$post->save()) {
//                   Yii::error("Failed to save post: " . json_encode($post->getErrors()), __METHOD__);
//              }
         }

          Yii::info("Finished executing AddToPost job", __METHOD__);
     }
}