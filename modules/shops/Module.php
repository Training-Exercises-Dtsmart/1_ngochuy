<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-24 16:26:28
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-08-02 14:53:48
 * @FilePath: modules/shops/Module.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\modules\shops;

class Module extends \yii\base\Module
{
     /**
      * {@inheritdoc}
      */
     public $controllerNamespace = 'app\modules\shops\controllers';
     
     /**
      * {@inheritdoc}
      */
     
     public function init()
     {
          parent::init();
          // custom initialization code goes here
     }
}