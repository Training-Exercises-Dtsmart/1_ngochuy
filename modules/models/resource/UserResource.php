<?php

namespace app\modules\models\resource;

use app\models\User;

class UserResource extends User
{
     public function fields()
     {
          return ['id', 'name', 'access_token'];
     }

}