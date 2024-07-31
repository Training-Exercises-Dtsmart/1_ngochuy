<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-31 15:56:19
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-31 17:07:31
 * @FilePath: services/OrderService.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\services;

use Yii;
use app\modules\shops\search\OrderSearch;
use yii\base\Component;
use yii\caching\CacheInterface;

class OrderService extends Component
{
     private $cache;
     
     public function __construct(CacheInterface $cache, $config = [])
     {
          $this->cache = $cache;
          parent::__construct($config);
     }
     
     public function getAllOrders($params)
     {
          $cacheKey = 'orders_index_' . md5(json_encode($params));
          $comments = $this->cache->get($cacheKey);

          if ($comments === false) {
               try {
                    $searchModel = new OrderSearch();
                    $comments = $searchModel->search($params);
                    $this->cache->set($cacheKey, $comments, 600);
               } catch (\Exception $e) {
                    Yii::error('Error fetching comments: ' . $e->getMessage(), __METHOD__);
                    throw $e;
               }
          }

          return $comments;  
     }
     
     public function clearOrderCache()
     {
          return $this->cache->delete('orders_index_*');
     }
}