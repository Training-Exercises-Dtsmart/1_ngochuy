<?php

namespace app\modules\users\resources;

use app\models\User;

class UserResource extends User
{
     public function fields()
     {
          return ['id', 'name', 'access_token'];
     }

}