<?php

namespace app\modules\shops\resources;

use app\modules\shops\models\Order;

class OrderResource extends Order
{
     public function fields()
     {
         return ['id', 'quantity', 'status', 'customer_id', 'total_amount', 'order_date', 'payment_method', 'shipping_address', 'created_at', 'updated_at', 'deleted_at'];
     }
}