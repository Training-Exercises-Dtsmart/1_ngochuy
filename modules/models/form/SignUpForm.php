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
     public $email;
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
               [['name', 'password', 'password_repeat', 'email'], 'required'],
               ['password', 'compare', 'compareAttribute' => 'password_repeat'],
               ['email', 'email'],
               ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email address has already been taken.'],
          ];
     }

     public function register()
     {
          if ($this->validate()) {
               $security = Yii::$app->security;

               $this->_user = new UserResource();
               $this->_user->name = $this->name;
               $this->_user->email = $this->email;
               $this->_user->password = $security->generatePasswordHash($this->password);
               $this->_user->access_token = $security->generateRandomString(255);
               $this->_user->verification_token = $security->generateRandomString() . '_' . time();
               if ($this->_user->save()) {
//                    $this->sendVerificationEmail($this->_user);
                    return true;
               }
               return false;
          }

          // if not passed then
          return false;
     }

     protected function sendVerificationEmail($user)
     {
          return Yii::$app->mailer->compose(['user' => $user])
               ->setFrom('no-reply@domain.com')
               ->setTo('huysanti123456@gmail.com')
               ->setSubject('Xin chào')
               ->setTextBody('Hello')
               ->setHtmlBody('<b>HTML content</b>')
               ->send();

     }
}
