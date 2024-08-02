<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-31 15:56:19
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-08-02 15:01:27
 * @FilePath: services/PostService.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\services;

use Yii;
use app\repositories\PostRepository;
use app\modules\shops\search\PostSearch;
use yii\base\Component;
use yii\caching\CacheInterface;

class PostService extends Component
{
     private $cache;
     private $postRepository;
     
     public function __construct(CacheInterface $cache, $config = [], PostRepository $postRepository)
     {
          $this->cache = $cache;
          $this->postRepository = $postRepository;
          parent::__construct($config);
     }
     
     public function getAllPosts($params)
     {
          $cacheKey = 'posts_index_' . md5(json_encode($params));
          $comments = $this->cache->get($cacheKey);

          if ($comments === false) {
               try {
                    $searchModel = new PostSearch();
                    $comments = $searchModel->search($params);
                    $this->cache->set($cacheKey, $comments, 600);
               } catch (\Exception $e) {
                    Yii::error('Error fetching comments: ' . $e->getMessage(), __METHOD__);
                    throw $e;
               }
          }

          return $comments;  
     }

     /**
      * Searches for payment types based on query parameters.
      *
      * @param array $queryParams
      * @return array
      */
     
     public function searchPosts(array $queryParams): array
     {
          $dataProvider = $this->postRepository->search($queryParams);
          return $dataProvider->getModels();
     }
     
     
     public function clearPostCache()
     {
          return $this->cache->delete('posts_index_*');
     }
}