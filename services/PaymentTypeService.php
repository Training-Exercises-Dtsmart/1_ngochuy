<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-31 15:56:19
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-08-01 10:24:29
 * @FilePath: services/PaymentTypeService.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\services;

use app\repositories\PaymentTypeRepository;
use Yii;
use app\modules\shops\search\PaymentTypeSearch;
use yii\base\Component;
use yii\caching\CacheInterface;

class PaymentTypeService extends Component
{
     private $cache;
     private $paymentTypeRepository;
     
     public function __construct(CacheInterface $cache, $config = [], PaymentTypeRepository $paymentTypeRepository)
     {
          $this->cache = $cache;
          $this->paymentTypeRepository = $paymentTypeRepository;
          parent::__construct($config);
     }
     
     public function getAllPaymentType($params)
     {
          $cacheKey = 'payment_types_index_' . md5(json_encode($params));
          $comments = $this->cache->get($cacheKey);

          if ($comments === false) {
               try {
                    $searchModel = new PaymentTypeSearch();
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
     
     public function searchPaymentTypes(array $queryParams): array
     {
          $dataProvider = $this->paymentTypeRepository->search($queryParams);
          return $dataProvider->getModels();
     }
     
     
     public function clearPaymentTypeCache()
     {
          return $this->cache->delete('payment_types_index_*');
     }
}