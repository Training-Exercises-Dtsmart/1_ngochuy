<?php

namespace app\modules\models\form;

use Yii;
use app\modules\models\Order;

class OrderForm extends Order
{
     public $id;
     public $email;
     public function rules()
     {
          return  [
                    [['payment_method', 'shipping_address'], 'required'],
                    [['payment_method', 'shipping_address', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
                    [['quantity'], 'integer'],
                    [['total_amount'], 'number'],
               ];
     }

     public function sendEmailToVendor()
     {
          return Yii::$app->mailer->compose(
               ['html' => 'order_completed_vendor-html', 'text' => 'order_completed_vendor-text'],
               ['order' => $this]
          )
               ->setFrom('huysanti12456@gmail.com')
               ->setTo('daominhhung2203@gmail.com')
               ->setSubject('New order has been made at: ' . Yii::$app->name)
               ->send();
     }

     public function sendEmailToCustomer()
     {
          return Yii::$app->mailer->compose(
               ['html' => 'order_completed_customer-html', 'text' => 'order_completed_customer-text'],
               ['order' => $this]
          )
               ->setFrom('huysanti12456@gmail.com')
               ->setTo('toan70868@gmail.')
               ->setSubject('Your order has been completed at: ' . Yii::$app->name)
               ->send();
     }

}