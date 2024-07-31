<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-31 15:56:19
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-31 16:09:53
 * @FilePath: services/CommentService.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\services;

use Yii;
use app\modules\shops\search\CommentSearch;
use yii\base\Component;
use yii\caching\CacheInterface;

class CommentService extends Component
{
     private $cache;
     
     public function __construct(CacheInterface $cache, $config = [])
     {
          $this->cache = $cache;
          parent::__construct($config);
     }
     
     public function getAllComments($params)
     {
          $cacheKey = 'comemnts_index_' . md5(json_encode($params));
          $comments = $this->cache->get($cacheKey);

          if ($comments === false) {
               try {
                    $searchModel = new CommentSearch();
                    $comments = $searchModel->search($params);
                    $this->cache->set($cacheKey, $comments, 600);
               } catch (\Exception $e) {
                    Yii::error('Error fetching comments: ' . $e->getMessage(), __METHOD__);
                    throw $e;
               }
          }

          return $comments;  
     }
     
     public function clearCommentCache()
     {
          $this->cache->delete('comments_index_*');
     }
}