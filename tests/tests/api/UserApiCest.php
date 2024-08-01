<?php


namespace tests\api;

use ApiTester;
use Faker\Factory;

class UserApiCest
{
     private $authToken;
     private $faker;

     public function _before(ApiTester $I)
     {
          // Initialize Faker
          $this->faker = Factory::create();

          // Authenticate and get the token
          $response = $I->sendPOST(env('API_UNIT_TEST') . 'users/user/login', [
               'name' => 'huysanti123',
               'password' => '123456',
          ]);
          $this->authToken = $I->grabDataFromResponseByJsonPath('$.data.data.access_token')[0];
     }

     public function _after(ApiTester $I)
     {

     }

     public function index(ApiTester $I)
     {
          $I->haveHttpHeader('Authorization', 'Bearer ' . $this->authToken);
          $I->sendGET(env('API_UNIT_TEST') . 'users/user');
          $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
          $I->seeResponseIsJson();
          $I->seeResponseJsonMatchesJsonPath('$.data.data[*].id');
          $I->seeResponseJsonMatchesJsonPath('$.data.data[*].*');
     }

     public function login(ApiTester $I)
     {
          $I->sendPOST(env('API_UNIT_TEST') . 'users/user/login', [
               'name' => 'huysanti123',
               'password' => '123456',
          ]);
          $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
          $I->seeResponseIsJson();
          $I->seeResponseJsonMatchesJsonPath('$.data.data[*]');
     }

     public function signUp(ApiTester $I)
     {
          $I->sendPOST(env('API_UNIT_TEST') . 'users/user/sign-up', [
               'name' => $this->faker->name,
               'email' => $this->faker->email,
               'password' => 'newpassword123',
               'password_repeat' => 'newpassword123',

          ]);
          $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
          $I->seeResponseIsJson();
          $I->seeResponseJsonMatchesJsonPath('$.data.data[*]');
     }

     public function logout(ApiTester $I)
     {
          $I->haveHttpHeader('Authorization', 'Bearer ' . $this->authToken);
          $I->sendPOST(env('API_UNIT_TEST') . 'users/user/logout');
          $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
          $I->seeResponseIsJson();
          $I->seeResponseJsonMatchesJsonPath('$.data');
     }

     public function updateUser(ApiTester $I)
     {
          $I->haveHttpHeader('Authorization', 'Bearer ' . $this->authToken);
          $I->sendPUT(env('API_UNIT_TEST') . 'users/user/update-user/?id=1', [
               'name' => $this->faker->name,
               'email' => $this->faker->email,
          ]);
          $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
          $I->seeResponseIsJson();
          $I->seeResponseJsonMatchesJsonPath('$.data');
     }

}
