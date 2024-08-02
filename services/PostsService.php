<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-31 15:56:19
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-08-02 15:01:27
 * @FilePath: services/PostsService.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\services;

use Yii;
use app\modules\shops\search\PostSearch;
use app\repositories\PostRepository;
use app\modules\shops\forms\PostForm;
use yii\caching\CacheInterface;
use yii\base\Component;

class PostsService extends Component
{
     private $postRepository;
     private $cache;

     public function __construct(CacheInterface $cache, $config = [],PostRepository $postRepository)
     {
          $this->cache = $cache;
          $this->postRepository = $postRepository;
          parent::__construct($config);
     }

     /**
      * Retrieves all posts.
      */
     public function getAllPosts($params)
     {
          $cacheKey = 'posts_index_' . md5(json_encode($params));
          $posts = $this->cache->get($cacheKey);

          if ($posts === false) {
               try {
                    $searchModel = new PostSearch();
                    $posts = $searchModel->search($params);
                    $this->cache->set($cacheKey, $posts, 600);
               } catch (\Exception $e) {
                    Yii::error('Error fetching posts: ' . $e->getMessage(), __METHOD__);
                    throw $e;
               }
          }

          return $posts;
     }
     /**
      * Searches for posts based on query parameters.
      *
      * @param array $queryParams
      * @return array
      */
     public function searchPosts(array $queryParams): array
     {
          $dataProvider = $this->postRepository->search($queryParams);
          return $dataProvider->getModels();
     }

     /**
      * Creates a new post.
      *
      * @param array $postData
      * @return bool
      */
     public function createPost(array $postData): bool
     {
          $postForm = new PostForm();
          $postForm->load($postData, '');
          if ($postForm->uploadMultipleImage($postData) && $postForm->validate()) {
               return $postForm->save();
          }
          return false;
     }

     /**
      * Updates an existing post.
      *
      * @param int $id
      * @param array $postData
      * @return bool
      */
     public function updatePost(int $id, array $postData): bool
     {
          $post = $this->postRepository->findOne($id);
          if (!$post) {
               return false;
          }

          $post->load($postData);
          return $post->validate() && $this->postRepository->save($post);
     }

     /**
      * Deletes a post.
      *
      * @param int $id
      * @return bool
      */
     public function deletePost(int $id): bool
     {
          $post = $this->postRepository->findOne($id);
          if (!$post) {
               return false;
          }

          return $this->postRepository->delete($post);
     }

     /**
      * @return void
      */
      public function clearPostCache():void
      {
            $this->cache->delete('posts_index_*');
      }
}
