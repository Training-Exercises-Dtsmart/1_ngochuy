<?php
/**
 * @Author: RobertPham0327 s3926681@rmit.edu.vn
 * @Date: 2024-07-19 15:23:44
 * @LastEditors: RobertPham0327 s3926681@rmit.edu.vn
 * @LastEditTime: 2024-07-19 15:58:46
 * @FilePath: mail/order_completed_vendor-text.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */

/** @var \app\models\base\Order $order */
?>

Order #<?php echo $order->id ?> summary:

Account information
Quantity: <?php echo $order->quantity ?>
Total Amount: <?php echo $order->total_amount ?>
Shipping Address : <?php echo $order->shipping_address ?>
Payment method : <?php echo $order->payment_method ?>

<p> Thanks for order from our product :>3</p>

