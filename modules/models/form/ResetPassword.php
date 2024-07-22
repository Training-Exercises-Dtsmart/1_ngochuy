<?php
/**
 * @Author: RobertPham0327 s3926681@rmit.edu.vn
 * @Date: 2024-07-22 17:02:57
 * @LastEditors: RobertPham0327 s3926681@rmit.edu.vn
 * @LastEditTime: 2024-07-22 17:05:42
 * @FilePath: modules/models/form/ResetPassword.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\modules\models\form;

use Yii;
use app\modules\models\User;
use yii\base\Model;

class ResetPassword extends Model
{
     public $password;
     /**
      * @var User
      */
     private $_user;

     /**
      * Creates a form model given a token.
      *
      * @param  string $token
      * @param  array $config name-value pairs that will be used to initialize the object properties
      * @throws \yii\base\InvalidParamException if token is empty or not valid
      */
     public function __construct($token, $config = [])
     {
          if (empty($token) || !is_string($token)) {
               throw new \HttpInvalidParamException('Password reset token cannot be blank.');
          }
          $this->_user = User::findByPasswordResetToken($token);
          if (!$this->_user) {
               throw new \HttpInvalidParamException('Wrong password reset token.');
          }
          parent::__construct($config);
     }

     /**
      * @inheritdoc
      */
     public function rules()
     {
          return [
               ['password', 'required'],
               ['password', 'string', 'min' => 6],
          ];
     }

     /**
      * Resets password.
      *
      * @return boolean if password was reset.
      * @throws \yii\base\Exception
      */
     public function resetPassword()
     {
          $user = $this->_user;
          $user->setPassword($this->password);
          $user->removePasswordResetToken();

          return $user->save(false);
     }
}