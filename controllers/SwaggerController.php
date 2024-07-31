<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-31 17:27:58
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-31 17:28:29
 * @FilePath: controllers/SwaggerController.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\controllers;

use Yii;
use yii\web\Controller;
use OpenApi\Annotations as OA;

class SwaggerController extends Controller
{
     /**
      * @OA\Info(title="My API", version="1.0.0")
      */
     public function actionJson()
     {
          $openapi = \OpenApi\Generator::scan([Yii::getAlias('@app/controllers')]);
          header('Content-Type: application/json');
          echo $openapi->toJson();
     }
}