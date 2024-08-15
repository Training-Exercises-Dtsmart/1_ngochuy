<?php


namespace tests\api;

use Faker\Factory;
use ApiTester;

class OrderApiCest
{
     private $authToken;
     private $faker;

     public function _before(ApiTester $I)
     {
          // Initialize Faker
          $this->faker = Factory::create();

          // Authenticate and get token
          $I->sendPost(env('API_UNIT_TEST') . 'users/user/login', [
               'name' => 'huysanti123',
               'password' => '123456'
          ]);
          $this->authToken = $I->grabDataFromResponseByJsonPath('$.data.data.access_token')[0];
     }

     public function _after(ApiTester $I)
     {
          // This method is called after each test.
     }

     /**
      * @param ApiTester $I The ApiTester instance used to perform the API request and assertions.
      * @return void
      */

     public function index(ApiTester $I)
     {
          $I->haveHttpHeader('Authorization', 'Bearer ' . $this->authToken);
          $I->sendGET(env('API_UNIT_TEST') . 'shops/order/index');
          $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
          $I->seeResponseIsJson();
          $I->seeResponseJsonMatchesJsonPath('$.data.orders.items[*].id');
          $I->seeResponseJsonMatchesJsonPath('$.data.orders.items[*].*');
     }


     /**
      * @param ApiTester $I
      * @return void
      */
     public function create(ApiTester $I)
     {
          $I->haveHttpHeader('Authorization', 'Bearer ' . $this->authToken);
          $I->sendPOST(env('API_UNIT_TEST') . 'shops/order/create', [
               'payment_method' => $this->faker->randomElement(['cash', 'credit_card', 'paypal']),
               'shipping_address' => $this->faker->address(),
          ]);
          $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
          $I->seeResponseIsJson();
          $I->seeResponseJsonMatchesJsonPath('$.data.order.*');
     }

}
