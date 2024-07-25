<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-24 16:26:01
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-25 14:33:40
 * @FilePath: modules/users/UserModule.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\modules\users;


class Module extends \yii\base\Module
{
     /**
      * {@inheritdoc}
      */
     public $controllerNamespace = 'app\modules\users\controllers';

     /**
      * {@inheritdoc}
      */

     public function init()
     {
          parent::init();
          // uncomment the following to add your IP if you are not connecting from localhost.
          // 'allowedIPs' => ['127.0.0.1', '::1'],     
          // custom initialization code goes here
     }
}