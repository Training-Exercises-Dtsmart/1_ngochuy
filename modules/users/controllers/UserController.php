<?php

namespace app\modules\users\controllers;

use app\components\RateLimitBehavior;
use app\modules\users\forms\SignInForm;
use Yii;
use app\components\CustomSerializer;
use app\repositories\UserRepository;
use app\controllers\Controller;
use app\modules\enums\HttpStatus;
use app\services\UserService;


class UserController extends Controller
{

     public $modelClass = 'app\models\User';

     public $serializer = [
          'class' => CustomSerializer::class,
          'collectionEnvelope' => 'items',
     ];

     private $userService;

     private $userRepository;

     public function __construct($id, $module, UserService $userService, UserRepository $userRepository, $config = [])
     {
          $this->userService = $userService;
          $this->userRepository = $userRepository;
          parent::__construct($id, $module, $config);
     }

     public function behaviors()
     {
          $behaviors = parent::behaviors();

          if (in_array($this->action->id, ['index', 'create', 'update', 'delete'])) {
               $behaviors['rateLimiter'] = [
                    'class' => RateLimitBehavior::class,
                    'enableRateLimitHeaders' => true,
               ];
          }
          return $behaviors;
     }
//    public function actionIndex()
//    {
//        $user = User::find();
//        if (!$user) {
//            return $this->json(false, [], 'User not found', HttpStatus::NOT_FOUND);
//        }
//        $pageSize = Yii::$app->request->get('pageSize', 20);
//        $dataProvider = Pagination::getPagination($user, $pageSize, SORT_DESC);
//        return $this->json(true, ['data' => $dataProvider], 'success', HttpStatus::OK);
//    }
//
//    public function actionCreate()
//    {
//        $user = new UserForm();
//        $user->load(Yii::$app->request->post(), '');
//        if (!$user->validate() || !$user->save()) {
//            return $this->json(false, $user->getErrors(), 'User not saved', HttpStatus::NOT_FOUND);
//        }
//        return $this->json(true, ['data' => $user], 'success', HttpStatus::OK);
//    }
//
//    public function actionLogin()
//    {
//        $model = new SignInForm();
//        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
//            return $this->json(true, ['data' => $model->getUser()->toArray(['id', 'name', 'access_token'])], 'Login successful', HttpStatus::OK);
//        }
//
//        return $this->json(false, $model->errors, 'Login failed', HttpStatus::UNAUTHORIZED);
//    }
//
//    public function actionSignUp()
//    {
//        $model = new SignupForm();
//        if ($model->load(Yii::$app->request->post(), '') && $model->register()) {
//            return $this->json(true, ['data' => $model->_user], 'Created account successful', HttpStatus::OK);
//        }
//
//        return $this->json(false, $model->getErrors(), 'Created account failed', HttpStatus::CONFLICT);
//    }
//
//    public function actionLogout()
//    {
//        $user = Yii::$app->user->identity;
//
//        if ($user) {
//            $user->access_token = '';
//            if ($user->save(false)) {
//                Yii::$app->user->logout();
//                return $this->json(true, [], 'Logout successful', HttpStatus::OK);
//            }
//        }
//
//        return $this->json(false, [], 'Logout failed', HttpStatus::UNAUTHORIZED);
//    }
//
//     public function actionVerifyEmail($token)
//     {
//          if (empty($token) || !is_string($token)) {
//               return $this->json(false, [], 'Verification token cannot be blank.', HttpStatus::BAD_REQUEST);
//          }
//
//          $user = User::findByVerificationToken($token);
//          if (!$user) {
//               return $this->json(false, [], 'Invalid verification token.', HttpStatus::BAD_REQUEST);
//          }
//
//          $user->status = User::STATUS_ACTIVE; // Assuming you have a status attribute to activate the user
//          $user->verification_token = null;
//
//          if ($user->save(false)) {
//               return $this->json(true, ['data' => $user], 'Account verified successfully.', HttpStatus::OK);
//          }
//
//          return $this->json(false, [], 'Verification failed.', HttpStatus::BAD_REQUEST);
//     }
//
//     public function actionUpdateUser($id)
//     {
//        $model = $this->findModel($id);
//        $model->setAttribute('password', null);
//
//        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
//            return $this->json(true, ['data' => $model], 'User updated successfully', HttpStatus::OK);
//        }
//
//        return $this->json(false, $model->getErrors(), 'User not updated', HttpStatus::NOT_FOUND);
//     }
//
//     public function actionSendmail()
//     {
//          Yii::$app->mailer->compose()
//               ->setFrom('no-reply@domain.com')
//               ->setTo('huysanti123456@gmail.com')
//               ->setSubject('Xin chào')
//               ->setTextBody('Hello')
//               ->setHtmlBody('<b>HTML content</b>')
//               ->send();
//     }
//
//     protected function findModel($id)
//     {
//          if (($model = User::findOne($id)) !== null) {
//               return $model;
//          }
//
//         return $this->json(false, [], 'User not found', HttpStatus::NOT_FOUND);
//     }

     public function actionIndex()
     {
          try {
               $dataProvider = $this->userService->getAllUsers(Yii::$app->request->queryParams);
               return $this->json(true, ['data' => $dataProvider], 'success', HttpStatus::OK);
          } catch (\Exception $e) {
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionCreate()
     {
          try {
               $result = $this->userService->createUser(Yii::$app->request->post());
               if (isset($result['errors'])) {
                    return $this->json(false, $result['errors'], 'User not saved', HttpStatus::NOT_FOUND);
               }
               return $this->json(true, ['data' => $result], 'success', HttpStatus::OK);
          } catch (\Exception $e) {
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
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
          try {
               $result = $this->userService->signUpUser(Yii::$app->request->post());
               if (isset($result['errors'])) {
                    return $this->json(false, $result['errors'], 'Created account failed', HttpStatus::CONFLICT);
               }
               return $this->json(true, ['data' => $result], 'Created account successful', HttpStatus::OK);
          } catch (\Exception $e) {
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionLogout()
     {
          try {
               $user = Yii::$app->user->identity;
               if ($user && $this->userService->logoutUser($user)) {
                    Yii::$app->user->logout();
                    return $this->json(true, [], 'Logout successful', HttpStatus::OK);
               }
               return $this->json(false, [], 'Logout failed', HttpStatus::UNAUTHORIZED);
          } catch (\Exception $e) {
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionVerifyEmail($token)
     {
          try {
               $result = $this->userService->verifyEmail($token);
               if (isset($result['errors'])) {
                    return $this->json(false, $result['errors'], 'Verification failed', HttpStatus::BAD_REQUEST);
               }
               return $this->json(true, ['data' => $result], 'Account verified successfully.', HttpStatus::OK);
          } catch (\Exception $e) {
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionUpdateUser($id)
     {
          try {
               $result = $this->userService->updateUser($id, Yii::$app->request->post());
               if (isset($result['errors'])) {
                    return $this->json(false, $result['errors'], 'User not updated', HttpStatus::NOT_FOUND);
               }
               return $this->json(true, ['data' => $result], 'User updated successfully', HttpStatus::OK);
          } catch (\Exception $e) {
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     public function actionSendmail()
     {
          try {
               Yii::$app->mailer->compose()
                    ->setFrom('no-reply@domain.com')
                    ->setTo('huysanti123456@gmail.com')
                    ->setSubject('Xin chào')
                    ->setTextBody('Hello')
                    ->setHtmlBody('<b>HTML content</b>')
                    ->send();
               return $this->json(true, [], 'Mail sent successfully', HttpStatus::OK);
          } catch (\Exception $e) {
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }

     protected function findModel($id)
     {
          try {
               $model = $this->userService->findModel($id);
               if (!$model) {
                    return $this->json(false, [], 'User not found', HttpStatus::NOT_FOUND);
               }
               return $model;
          } catch (\Exception $e) {
               return $this->json(false, ['errors' => $e->getMessage()], 'Internal Server Error', HttpStatus::INTERNAL_SERVER_ERROR);
          }
     }
}