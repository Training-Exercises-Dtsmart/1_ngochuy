<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-08-03 18:27:54
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-08-03 18:43:36
 * @FilePath: commands/EmailController.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */

namespace app\commands;

use Yii;
use yii\console\Controller;

class EmailController extends Controller
{
     public function actionSendDailyEmail()
     {
          $recipientEmail = 'daominhhung2203@gmail.com';
          
          Yii::$app->mailer->compose()
               ->setFrom('huysanti12456@gmail.com')
               ->setTo($recipientEmail)
               ->setSubject('Daily Check Attendance')
               ->setTextBody('This is your daily attendance report')
               ->send();
          
          echo "Daily email sent to {$recipientEmail}.\n";
     }     
}