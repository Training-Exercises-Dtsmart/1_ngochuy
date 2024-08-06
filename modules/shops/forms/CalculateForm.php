<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-08-05 16:30:57
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-08-05 17:12:49
 * @FilePath: modules/shops/forms/CalculateForm.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */

namespace app\modules\shops\forms;

use app\models\base\Number;

class CalculateForm extends Number
{
     public function saveNumber()
     {
          if ($this->validate()) {
               $result = new \app\models\Number();
               $result = $this->a * $this->b;
               if (strlen((string)$result) <= 255) {
                    $this->result = $result;
                    return $this->save();
               }
          }
          return false;
     }
}     