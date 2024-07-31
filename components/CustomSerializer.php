<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-31 14:13:54
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-31 14:36:58
 * @FilePath: components/CustomSerializer.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\components;
use yii\rest\Serializer;

class CustomSerializer extends Serializer
{
     public function serialize($data)
     {
          $result = parent::serialize($data);

          if (isset($result['_links'])) {
               unset($result['_links']);
          }
          return $result;
     }
}