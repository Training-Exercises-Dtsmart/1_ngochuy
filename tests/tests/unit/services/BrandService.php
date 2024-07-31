<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-31 15:12:51
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-31 15:15:43
 * @FilePath: tests/unit/services/BrandService.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */

namespace tests\unit\services;

use app\services\BrandService;
use app\modules\shops\search\BrandSearch;
use yii\caching\ArrayCache;
use yii\base\InvalidConfigException;

class BrandServiceTest extends \Codeception\Test\Unit
{
     /**
      * @var \UnitTester
      */
     protected $tester;

     /**
      * @var BrandService
      */
     protected $brandService;

     protected function _before()
     {
          $cache = new ArrayCache();
          $this->brandService = new BrandService($cache, 600);
     }

     public function testGetAllBrands()
     {
          $params = ['some_param' => 'some_value'];
          $brands = $this->brandService->getAllBrands($params);

          $this->assertIsArray($brands);
     }

     public function testClearBrandCache()
     {
          $this->brandService->clearBrandCache();
          $this->assertTrue(true); // Add appropriate assertions
     }
}

