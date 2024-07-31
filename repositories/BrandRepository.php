<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-31 14:59:39
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-31 15:17:50
 * @FilePath: repositories/BrandRepository.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\repositories;

use app\modules\shops\models\Brand;
use yii\db\ActiveRecord;

/**
 * Repository class for accessing brand data.
 */
class BrandRepository
{
     /**
      * Retrieves all brands.
      * @return array|ActiveRecord[]
      */
     public function findAll(): array
     {
          return Brand::find()->all();
     }

     /**
      * Finds a brand by its ID.
      * @param int $id
      * @return Brand|null
      */
     public function findOne(int $id): ?Brand
     {
          return Brand::findOne($id);
     }

     /**
      * Saves a brand.
      * @param Brand $brand
      * @return bool
      */
     public function save(Brand $brand): bool
     {
          return $brand->save();
     }

     /**
      * Deletes a brand.
      * @param Brand $brand
      * @return bool
      */
     public function delete(Brand $brand): bool
     {
          return $brand->delete();
     }
}
