<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-31 15:56:01
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-31 17:07:19
 * @FilePath: repositories/OrderRepository.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\repositories;

use app\modules\shops\models\Order;
use yii\db\ActiveRecord;
class OrderRepository
{
     /**
      * Retrieves all brands.
      * @return array|ActiveRecord[]
      */
     public function findAll(): array
     {
          return Order::find()->all();
     }

     /**
      * Finds a brand by its ID.
      * @param int $id
      * @return Order|null
      */
     public function findOne(int $id): ?Order
     {
          return Order::findOne($id);
     }

     /**
      * Saves a brand.
      * @param Order $order
      * @return bool
      */
     public function save(Order $order): bool
     {
          return $order->save();
     }

     /**
      * Deletes a brand.
      * @param Order $order
      * @return bool
      */
     public function delete(Order $order): bool
     {
          return $order->delete();
     }
}