<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-25 14:27:45
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-08-02 14:53:48
 * @FilePath: modules/api/modules/v1/Module.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */

namespace app\modules\api\modules\v1;

/**
 * v1 module definition class
 */
class Module extends \yii\base\Module
{
     /**
      * {@inheritdoc}
      */
     public $controllerNamespace = 'app\modules\api\modules\v1\controllers';

     /**
      * {@inheritdoc}
      */
     public function init()
     {
          parent::init();
          // custom initialization code for v1 module
     }
}