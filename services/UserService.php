<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-31 15:56:19
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-08-05 11:43:37
 * @FilePath: services/UserService.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\services;

use app\modules\models\User;
use app\modules\users\forms\SignInForm;
use app\modules\users\forms\SignupForm;
use app\modules\users\forms\UserForm;
use Yii;
use app\repositories\UserRepository;
use app\modules\users\search\UserSearch;
use yii\base\Component;
use yii\caching\CacheInterface;

class UserService extends Component
{
     private $cache;
     private $userRepository;
     
     public function __construct(CacheInterface $cache, $config = [], UserRepository $userRepository)
     {
          $this->cache = $cache;
          $this->userRepository = $userRepository;
          parent::__construct($config);
     }
     
     public function getAllUsers($params)
     {
          $cacheKey = 'users_index_' . md5(json_encode($params));
          $comments = $this->cache->get($cacheKey);

          if ($comments === false) {
               try {
                    $searchModel = new UserSearch();
                    $comments = $searchModel->search($params);
                    $this->cache->set($cacheKey, $comments, 600);
               } catch (\Exception $e) {
                    Yii::error('Error fetching comments: ' . $e->getMessage(), __METHOD__);
                    throw $e;
               }
          }

          return $comments;  
     }


     /**
      * Searches for payment types based on query parameters.
      *
      * @param array $queryParams
      * @return array
      */
     
     public function searchUsers(array $queryParams): array
     {
          $dataProvider = $this->userRepository->search($queryParams);
          return $dataProvider->getModels();
     }


     public function clearUserCache()
     {
          return $this->cache->delete('users_index_*');
     }

     
     public function createUser($postData)
     {
          $userForm = new UserForm();
          $userForm->load($postData, '');
          if (!$userForm->validate() || !$userForm->save()) {
               return ['errors' => $userForm->getErrors()];
          }
          return $userForm;
     }

     public function loginUser($postData)
     {
          $signInForm = new SignInForm();
          if ($signInForm->load($postData, '') && $signInForm->login()) {
               return $signInForm->getUser();
          }
          return ['errors' => $signInForm->errors];
     }

     public function signUpUser($postData)
     {
          $signUpForm = new SignupForm();
          if ($signUpForm->load($postData, '') && $signUpForm->register()) {
               return $signUpForm->_user;
          }
          return ['errors' => $signUpForm->getErrors()];
     }

     public function verifyEmail($token)
     {
          $user = $this->userRepository->findByVerificationToken($token);
          if (!$user) {
               return ['errors' => 'Invalid verification token.'];
          }
          $user->status = User::STATUS_ACTIVE;
          $user->verification_token = null;

          if ($this->userRepository->save($user)) {
               return $user;
          }
          return ['errors' => 'Verification failed.'];
     }

     public function updateUser($id, $postData)
     {
          $user = $this->userRepository->findOne($id);
          if (!$user) {
               return ['errors' => 'User not found.'];
          }
          $user->load($postData, '');
          if ($user->validate() && $user->save()) {
               return $user;
          }
          return ['errors' => $user->getErrors()];
     }

     public function logoutUser(User $user)
     {
          $user->access_token = '';
          if ($user->save(false)) {
               return true;
          }
          return false;
     }
}