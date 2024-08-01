<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-31 15:56:19
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-08-01 11:03:06
 * @FilePath: services/ProductService.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\services;

use Yii;
use app\repositories\ProductRepository;
use app\modules\shops\search\ProductSearch;
use yii\base\Component;
use yii\caching\CacheInterface;

class ProductService extends Component
{
     private $cache;
     private $productRepository;
     
     public function __construct(CacheInterface $cache, $config = [], ProductRepository $productRepository)
     {
          $this->cache = $cache;
          $this->productRepository = $productRepository;
          parent::__construct($config);
     }
     
     public function getAllProducts($params)
     {
          $cacheKey = 'products_index_' . md5(json_encode($params));
          $comments = $this->cache->get($cacheKey);

          if ($comments === false) {
               try {
                    $searchModel = new ProductSearch();
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
     
     public function searchProducts(array $queryParams): array
     {
          $dataProvider = $this->productRepository->search($queryParams);
          return $dataProvider->getModels();
     }
     
     
     public function clearProductCache()
     {
          return $this->cache->delete('products_index_*');
     }
}