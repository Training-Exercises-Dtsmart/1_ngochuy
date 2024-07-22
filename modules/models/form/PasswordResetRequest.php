<?php
/**
 * @Author: RobertPham0327 s3926681@rmit.edu.vn
 * @Date: 2024-07-22 16:56:53
 * @LastEditors: RobertPham0327 s3926681@rmit.edu.vn
 * @LastEditTime: 2024-07-22 17:01:47
 * @FilePath: modules/models/form/PasswordResetRequest.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\modules\models\form;

use app\modules\models\User;
use yii\base\Model;

class PasswordResetRequest extends Model
{
     public $email;

     /**
      * @inheritdoc
      */
     public function rules()
     {
          return [
               ['email', 'filter', 'filter' => 'trim'],
               ['email', 'required'],
               ['email', 'email'],
               ['email', 'exist',
                    'targetClass' => 'mdm\admin\models\User',
                    'filter' => ['status' => User::STATUS_ACTIVE],
                    'message' => 'There is no user with such email.'
               ],
          ];
     }

     /**
      * Sends an email with a link, for resetting the password.
      *
      * @return boolean whether the email was send
      * @throws \yii\base\Exception
      */
     public function sendEmail()
     {
          /* @var $user User */
          $user = User::findOne([
               'status' => User::STATUS_ACTIVE,
               'email' => $this->email,
          ]);

          if ($user) {
               if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                    $user->generatePasswordResetToken();
               }

               if ($user->save()) {
                    return Yii::$app->mailer->compose(['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['user' => $user])
                         ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . 'robot'])
                         ->setTo(Yii::$app->params['vendorEmail'])
                         ->setSubject('Password reset for ' . Yii::$app->name)
                         ->send();
               }
          }

          return false;
     }
}