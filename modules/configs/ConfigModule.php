<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-24 14:36:09
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-24 14:36:54
 * @FilePath: modules/configs/ConfigModule.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */
namespace app\modules\config;

use app;
use app\components\BaseModule;
use Yii;

/**
 * Base configuration module for DotPlant2 CMS
 * @package app\modules\config
 */
class ConfigModule extends BaseModule
{
     public $controllerMap = [
          'backend' => 'app\modules\config\backend\ConfigController',
     ];

     /**
      *
      */
     public function init()
     {
          parent::init();
          if (Yii::$app instanceof \yii\console\Application) {
               $this->controllerMap = [];
          }
     }
}


