<?php

namespace app\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller as BaseController;

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
              HttpBearerAuth::class,
         ];

         $behaviors['authenticator']['except'] = ['options', 'login', 'sign-up'];
       //   $behaviors['cors'] = [
       //        'class' => Cors::class
       //   ];

         return $behaviors;
    }
}