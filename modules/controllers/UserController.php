<?php

namespace app\modules\controllers;

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

    // public function actionLogin()
    // {
    //      $model = new LoginForm();
    //      if ($model->load(\Yii::$app->request->post(), '') && $model->login()) {
    //           return $model->getUser()->toArray(['id', 'access_token', 'username']);
    //      }

    //      \Yii::$app->response->statusCode = 422;
    //      return [
    //           'errors' => $model->errors
    //      ];
    // }

    public function actionSignUp()
    {
         $model = new SignupForm(); 
         if ($model->load(Yii::$app->request->post(), '') && $model->register()) {
              return $model->_user;
         }

         Yii::$app->response->statusCode = 422;
         return [
              'errors' => $model->errors
         ];
    }
}