<?php

namespace app\modules\controllers;

use app\modules\models\form\SignInForm;
use app\modules\models\form\SignupForm;
use Yii;
use app\modules\models\form\UserForm;
use app\models\User;
use app\controllers\Controller;
use app\modules\models\HttpStatus;
use app\modules\models\pagination\Pagination;

class UserController extends Controller
{
    public function actionIndex()
    {
        $user = User::find();
        if (!$user) {
            return $this->json(false, [], 'User not found', HttpStatus::NOT_FOUND);
        }

        $dataProvider = Pagination::getPagination($user, 10, SORT_DESC);
        return $this->json(true, ['data' => $dataProvider], 'success', HttpStatus::OK);
    }

    public function actionCreate()
    {
        $user = new UserForm();
        $user->load(Yii::$app->request->post(), '');
        if (!$user->validate() || !$user->save()) {
            return $this->json(false, $user->getErrors(), 'User not saved', HttpStatus::NOT_FOUND);
        }
        return $this->json(true, ['data' => $user], 'success', HttpStatus::OK);
    }

    public function actionLogin()
    {
        $model = new SignInForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
            return $this->json(true, ['data' => $model->getUser()->toArray(['id', 'name', 'access_token'])], 'Login successful', HttpStatus::OK);
        }

        return $this->json(false, $model->errors, 'Login failed', HttpStatus::UNAUTHORIZED);
    }

    public function actionSignUp()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->register()) {
            return $this->json(true, ['data' => $model->_user], 'Created account successful', HttpStatus::OK);
        }

        return $this->json(false, $model->getErrors(), 'Created account failed', HttpStatus::CONFLICT);
    }

    public function actionLogout()
    {
        $user = Yii::$app->user->identity;

        if ($user) {
            $user->access_token = '';
            if ($user->save(false)) {
                Yii::$app->user->logout();
                return $this->json(true, [], 'Logout successful', HttpStatus::OK);
            }
        }

        return $this->json(false, [], 'Logout failed', HttpStatus::UNAUTHORIZED);
    }

     public function actionVerifyEmail($token)
     {
          if (empty($token) || !is_string($token)) {
               return $this->json(false, [], 'Verification token cannot be blank.', HttpStatus::BAD_REQUEST);
          }

          $user = User::findByVerificationToken($token);
          if (!$user) {
               return $this->json(false, [], 'Invalid verification token.', HttpStatus::BAD_REQUEST);
          }

          $user->status = User::STATUS_ACTIVE; // Assuming you have a status attribute to activate the user
          $user->verification_token = null;

          if ($user->save(false)) {
               return $this->json(true, ['data' => $user], 'Account verified successfully.', HttpStatus::OK);
          }

          return $this->json(false, [], 'Verification failed.', HttpStatus::BAD_REQUEST);
     }

     public function actionSendmail()
     {
          Yii::$app->mailer->compose()
               ->setFrom('no-reply@domain.com')
               ->setTo('huysanti123456@gmail.com')
               ->setSubject('Xin chào')
               ->setTextBody('Hello')
               ->setHtmlBody('<b>HTML content</b>')
               ->send();
     }
}