<?php

namespace app\modules\models\form;

use app\modules\models\Order;

class OrderForm extends Order
{
     public function rules()
     {
       return array_merge(
           parent::rules(),
           [
                [['payment_method', 'shipping_address'], 'required'],
                [['payment_method', 'shipping_address', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
               [['quantity'], 'integer'],
               [['total_amount'], 'number'],
           ]
       );
     }


}