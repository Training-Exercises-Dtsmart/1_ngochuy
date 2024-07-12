<?php

namespace app\modules\models\form;

use app\models\User;
use app\modules\models\resource\UserResource;
use Yii;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class SignupForm extends \app\models\form\LoginForm
{
     public $name;
     public $password;

     public $password_repeat;
     public $_user = false;


     /**
      * @return array the validation rules.
      */
     public function rules()
     {
          return [
               ['name', 'unique', 'targetClass' => UserResource::class, 'message' => 'This username has already been taken.'],
               [['name', 'password', 'password_repeat'], 'required'],
               ['password', 'compare', 'compareAttribute' => 'password_repeat'],
          ];
     }

     public function register()
     {
          if ($this->validate()) {
               $security = Yii::$app->security;

               $this->_user = new UserResource();
               $this->_user->name = $this->name;
               $this->_user->password = $security->generatePasswordHash($this->password);
               $this->_user->access_token = $security->generateRandomString(255);
               if ($this->_user->save()) {
                    return true;
               }
               return false;
          }

          // if not passed then
          return false;
     }
}
