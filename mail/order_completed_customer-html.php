<?php
/**
 * @Author: RobertPham0327 s3926681@rmit.edu.vn
 * @Date: 2024-07-19 15:22:12
 * @LastEditors: RobertPham0327 s3926681@rmit.edu.vn
 * @LastEditTime: 2024-07-19 15:59:14
 * @FilePath: mail/order_completed_customer-html.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


/** @var \app\models\base\Order $order */


?>
<style>
    .row {
        display: flex;
    }
    .col {
        flex: 1;
    }
</style>
<h3>Order #<?php echo $order->id ?> summary: </h3>
<hr>
<div class="row">
     <div class="col">
          <h5>Account information</h5>
          <table class="table">
               <tr>
                    <th>Quantity</th>
                    <td><?php echo $order->quantity ?></td>
               </tr>
               <tr>
                    <th>Total_amount</th>
                    <td><?php echo $order->total_amount ?></td>
               </tr>
               <tr>
                    <th>Shipping Address</th>
                    <td><?php echo $order->shipping_address ?></td>
               </tr>
              <tr>
                    <th>Payment method</th>
                    <td><?php echo $order->payment_method ?></td>
               </tr>
          </table>
          <h5>Address information</h5>
     </div>
</div>