<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-08-01 10:36:14
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-08-01 10:48:05
 * @FilePath: repositories/PostRepository.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\repositories;

use app\modules\shops\models\Post;
use app\modules\shops\search\PostSearch;
use yii\data\ActiveDataProvider;

class PostRepository
{
     /**
      * Finds a post by its ID.
      *
      * @param int $id
      * @return Post|null
      */
     public function findOne(int $id): ?Post
     {
          return Post::findOne($id);
     }

     /**
      * Saves a post.
      *
      * @param Post $post
      * @return bool
      */
     public function save(Post $post): bool
     {
          return $post->save();
     }

     /**
      * Deletes a post.
      *
      * @param Post $post
      * @return bool
      */
     public function delete(Post $post): bool
     {
          return $post->delete();
     }

     /**
      * Searches for posts based on query parameters.
      *
      * @param array $queryParams
      * @return ActiveDataProvider
      */
     public function search(array $queryParams): ActiveDataProvider
     {
          $searchModel = new PostSearch();
          return $searchModel->search($queryParams);
     }
}
