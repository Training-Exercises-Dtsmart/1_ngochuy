<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-31 15:56:01
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-08-01 11:03:06
 * @FilePath: repositories/ProductRepository.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\repositories;

use Yii;
use app\modules\shops\models\Product;
use app\modules\shops\search\ProductSearch;
use yii\db\ActiveRecord;

class ProductRepository
{
     /**
      * Retrieves all payment types.
      * @return array|ActiveRecord[]
      */
     public function findAll(): array
     {
          return Product::find()->all();
     }

     /**
      * Finds a payment type by its ID.
      * @param int $id
      * @return Product null
      */
     public function findOne(int $id): ?Product 
     {
          return Product ::findOne($id);
     }

     /**
      * Saves a payment type.
      * @param Product $product
      * @return bool
      */
     public function save(Product  $product): bool
     {
          return $product->save(false); // Bypass validation here, as it's already done.
     }

     /**
      * Deletes a payment type.
      * @param Product $product
      * @return bool
      */
     public function delete(Product $product): bool
     {
          return $product->delete() != false;
     }

     /**
      * Searches for payment types based on query parameters.
      *
      * @param array $queryParams
      * @return \yii\data\ActiveDataProvider
      */
     public function search(array $queryParams)
     {
          $searchModel = new ProductSearch();
          return $searchModel->search($queryParams);
     }
}