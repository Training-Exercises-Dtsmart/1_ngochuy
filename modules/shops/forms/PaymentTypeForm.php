<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-25 15:55:05
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-08-01 11:18:13
 * @FilePath: modules/shops/forms/PaymentTypeForm.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\modules\shops\forms;

use app\models\PaymentType;

class PaymentTypeForm extends PaymentType
{
      public $name;
      public $description;
      
      public function rules()
      {
            return [
                  [['name', 'description' ], 'required'],
                  [['name', 'description'], 'string', 'max' => 255],
            ];
      }
}