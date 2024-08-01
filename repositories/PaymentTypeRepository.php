<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-31 15:56:01
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-08-01 10:02:05
 * @FilePath: repositories/PaymentTypeRepository.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\repositories;

use Yii;
use app\modules\shops\models\PaymentType;
use app\modules\shops\search\PaymentTypeSearch;
use yii\db\ActiveRecord;

class PaymentTypeRepository
{
     /**
      * Retrieves all payment types.
      * @return array|ActiveRecord[]
      */
     public function findAll(): array
     {
          return PaymentType::find()->all();
     }

     /**
      * Finds a payment type by its ID.
      * @param int $id
      * @return PaymentType null
      */
     public function findOne(int $id): ?PaymentType 
     {
          return PaymentType ::findOne($id);
     }

     /**
      * Saves a payment type.
      * @param PaymentType $paymentType
      * @return bool
      */
     public function save(PaymentType  $paymentType): bool
     {
          return $paymentType->save(false); // Bypass validation here, as it's already done.
     }

     /**
      * Deletes a payment type.
      * @param PaymentType $paymentType
      * @return bool
      */
     public function delete(PaymentType $paymentType): bool
     {
          return $paymentType->delete() != false;
     }

     /**
      * Searches for payment types based on query parameters.
      *
      * @param array $queryParams
      * @return \yii\data\ActiveDataProvider
      */
     public function search(array $queryParams)
     {
          $searchModel = new PaymentTypeSearch();
          return $searchModel->search($queryParams);
     }
}