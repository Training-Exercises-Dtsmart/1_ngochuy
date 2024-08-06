<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-08-05 16:23:16
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-08-05 17:00:29
 * @FilePath: modules/maths/controllers/CalculatorController.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\modules\maths\controllers;

use app\controllers\Controller;
use app\modules\enums\HttpStatus;
use Yii;

class CalculatorController extends Controller
{
     public function actionMultiple()
     {
          $a = Yii::$app->input->post('a');
          $b = Yii::$app->input->post('b');
          
          $result = $a * $b;
          
          return $this->json(true, ['result' => $result], "Success", HttpStatus::OK);
//          if (!is_numeric($a) || !is_numeric($b)) {
//               return $this->json(false, ['error' => 'a or b is not a valid number'], 'Failed', HttpStatus::BAD_REQUEST);
//          }
//          
//          $result = new CalculateForm();
//          $result->load(Yii::$app->request->post(), '');
//          if (!$result->validate() || !$result->save()) {
//               return $this->json(false, ['errors' => $result->getErrors()], "Bad request", HttpStatus::BAD_REQUEST);
//          }
//          
//          return $this->json(true, ['result' => $result], 'Successed', HttpStatus::OK);    
     }
}