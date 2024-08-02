<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-31 15:56:01
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-08-02 15:05:10
 * @FilePath: repositories/PostRepository.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\repositories;

use Yii;
use app\modules\shops\models\Post;
use app\modules\shops\search\PostSearch;
use yii\db\ActiveRecord;

class PostRepository
{
     /**
      * Retrieves all posts.
      * @return array|ActiveRecord[]
      */
     public function findAll(): array
     {
          return Post::find()->all();
     }

     /**
      * Finds a post by its ID.
      * @param int $id
      * @return Post null
      */
     public function findOne(int $id): ?Post
     {
          return Post ::findOne($id);
     }

     /**
      * Saves a payment type.
      * @param Post $posts
      * @return bool
      */
     public function save(Post $posts): bool
     {
          return $posts->save(false); // Bypass validation here, as it's already done.
     }

     /**
      * Deletes a payment type.
      * @param Post $posts
      * @return bool
      */
     public function delete(Post $posts): bool
     {
          return $posts->delete() != false;
     }

     /**
      * Searches for posts based on query parameters.
      *
      * @param array $queryParams
      * @return \yii\data\ActiveDataProvider
      */
     public function search(array $queryParams)
     {
          $searchModel = new PostSearch();
          return $searchModel->search($queryParams);
     }
}