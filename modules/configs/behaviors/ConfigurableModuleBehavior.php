<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-24 13:56:56
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-24 14:00:48
 * @FilePath: modules/configs/behaviors/ConfigurableModuleBehavior.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */

namespace app\modules\config\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\Module;
use yii\helpers\StringHelper;

/**
 * Tự động nạp cấu hình: Behavior này tự động nạp các giá trị cấu hình cho module từ mảng PHP trước khi bất kỳ hành động nào trong module được thực hiện.
 * Truy xuất cấu hình dễ dàng: Nó cung cấp một phương thức tiện lợi để truy xuất các giá trị cấu hình dựa trên khóa.
 * Tăng tính modular: Behavior này giúp tăng tính modular và linh hoạt của mã bằng cách cho phép dễ dàng cấu hình các module mà không cần thay đổi mã chính của module.
 *
 */


/**
 * Behavior  modules for configuration support in API
 * @package app\behaviors
 */
class ConfigurableModuleBehavior extends Behavior
{
     /**
      * @var string Returns Model name, that should handle configuration of module
      */
     public $configurableModel = null;

     /**
      * @var array Stores config values array
      */
     private $configValues = null;

     /**
      * return array events for module
      */
     public function events()
     {
          return [
               Module::EVENT_BEFORE_ACTION => 'preloadConfigValues',
          ];
     }

     /**
      * Preloads configuration values from php files that stores php array
      */
     public function preloadConfigValues()
     {
          if ($this->configValues === null) {
               $ownerName = StringHelper::basename(get_class($this->owner));

               if (isset(Yii::$app->params['kv-'.$ownerName]) === true) {
                    $this->configValues = Yii::$app->params['kv-'.$ownerName];
               } else {
                    // config is empty for now
                    $this->configValues = [];
               }
          }
     }

     /**
      * Returns key-value config value
      * @param string $key
      * @param null|mixed $defaultValue
      */
     public function getConfigValue($key, $defaultValue = null)
     {
          $this->preloadConfigValues();
          if (isset($this->configValues[$key]) === true) {
               return $this->configValues[$key];
          } else {
               return $defaultValue;
          }
     }
}
