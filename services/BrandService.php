<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-31 14:42:54
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-31 15:19:00
 * @FilePath: services/BrandService.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\services;

use Yii;
use app\modules\shops\search\BrandSearch;
use yii\base\Component;
use yii\caching\CacheInterface;

class BrandService extends Component
{
     private $cache;

     public function __construct(CacheInterface $cache, $config = [])
     {
          $this->cache = $cache;
          parent::__construct($config);
     }

     public function getAllBrands($params)
     {
          $cacheKey = 'brands_index_' . md5(json_encode($params));
          $brands = $this->cache->get($cacheKey);

          if ($brands === false) {
               try {
                    $searchModel = new BrandSearch();
                    $brands = $searchModel->search($params);
                    $this->cache->set($cacheKey, $brands, 600);
               } catch (\Exception $e) {
                    Yii::error('Error fetching brands: ' . $e->getMessage(), __METHOD__);
                    throw $e;
               }
          }

          return $brands;
     }

     public function clearBrandCache()
     {
          $this->cache->delete('brands_index_*');
     }
}