<?php

namespace app\controllers;

use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\Controller as BaseController;
use yii\filters\Cors;

class Controller extends BaseController
{
     public function json($status = true, $data = [], $message = "", $code = 200): array
     {
          Yii::$app->response->statusCode = $code;
          return [
               "status" => $status,
               "data" => $data,
               "message" => $message,
               "code" => $code
          ];
     }

     public function behaviors()
     {
          $behaviors = parent::behaviors();
          $behaviors['authenticator']['authMethods'] = [
               HttpBasicAuth::class,
               HttpBearerAuth::class,
               QueryParamAuth::class
          ];

          $behaviors['authenticator']['except'] = ['options', 'login', 'sign-up'];
          $behaviors['cors'] = [
               'class' => Cors::class
          ];

          return $behaviors;
     }
}