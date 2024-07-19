<?php
/**
 * @Author: RobertPham0327 s3926681@rmit.edu.vn
 * @Date: 2024-07-19 17:06:58
 * @LastEditors: RobertPham0327 s3926681@rmit.edu.vn
 * @LastEditTime: 2024-07-19 17:33:31
 * @FilePath: modules/controllers/SmsController.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */

namespace app\modules\controllers;

use Yii;
use yii\rest\Controller;

class SmsController extends Controller  
{
     public function actionSendSms()
     {
          
//          $phoneNumber = '84799526520'; // Vietnamese phone number (omit the leading '+' sign)
          $phoneNumber = '84359221014';
          $message = 'Hello from Yii2 via Nexmo!';

          $nexmo = Yii::$app->nexmo;
          try {
               $response = $nexmo->sendSms($phoneNumber, $message);
               if ($response->current()->getStatus() == 0) {
                    return 'SMS sent successfully!';
               } else {
                    return 'SMS failed with status: ' . $response->current()->getStatus();
               }
          } catch (\Exception $e) {
               return 'Error: ' . $e->getMessage();
          }
     }
     
}