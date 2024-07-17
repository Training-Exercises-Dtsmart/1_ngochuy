<?php

namespace app\components;

use Yii;
use yii\base\Component;
use function igorw\retry;

class CommonComponent extends Component
{
     public function getToken()
     {
          return 'your_token_here';
     }

     public function getData()
     {
          $data = Yii::$app->db->createCommand('select * from products')->queryAll();
          return $data;
     }

}