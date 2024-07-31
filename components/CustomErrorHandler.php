<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-31 15:47:05
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-07-31 15:47:33
 * @FilePath: components/CustomErrorHandler.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\components;

use Yii;
use yii\web\ErrorHandler;
use yii\web\HttpException;

class CustomErrorHandler extends ErrorHandler
{
     protected function renderException($exception)
     {
          if ($exception instanceof HttpException && $exception->statusCode == 429) {
               Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
               Yii::$app->response->statusCode = 429;
               Yii::$app->response->data = [
                    'success' => false,
                    'message' => 'Rate limit exceeded. Please try again later.',
                    'code' => 429,
               ];
               return;
          }

          parent::renderException($exception);
     }
}
