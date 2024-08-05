<?php
/**
 * @Author: JustABusiness huysanti123456@gmail.com
 * @Date: 2024-07-31 15:56:01
 * @LastEditors: JustABusiness huysanti123456@gmail.com
 * @LastEditTime: 2024-08-05 11:37:27
 * @FilePath: repositories/UserRepository.php
 * @Description: 这是默认设置,可以在设置》工具》File Description中进行配置
 */


namespace app\repositories;

use Yii;
use app\modules\users\models\User;
use app\modules\users\search\UserSearch;
use yii\db\ActiveRecord;

class UserRepository
{
     /**
      * Retrieves all payment types.
      * @return array|ActiveRecord[]
      */
     public function findAll(): array
     {
          return User::find()->all();
     }

     /**
      * Finds a payment type by its ID.
      * @param int $id
      * @return User null
      */
     public function findOne(int $id): ?User 
     {
          return User ::findOne($id);
     }

     /**
      * Finds a user by verification token.
      * @param string $token
      * @return User|null
      */
     public function findByVerificationToken(string $token): ?User
     {
          return User::findByVerificationToken($token);
     }
     
     /**
      * Saves a payment type.
      * @param User $user
      * @return bool
      */
     public function save(User  $user): bool
     {
          return $user->save(false); // Bypass validation here, as it's already done.
     }

     /**
      * Deletes a payment type.
      * @param User $user
      * @return bool
      */
     public function delete(User $user): bool
     {
          return $user->delete() != false;
     }

     /**
      * Searches for payment types based on query parameters.
      *
      * @param array $queryParams
      * @return \yii\data\ActiveDataProvider
      */
     public function search(array $queryParams)
     {
          $searchModel = new UserSearch();
          return $searchModel->search($queryParams);
     }
}