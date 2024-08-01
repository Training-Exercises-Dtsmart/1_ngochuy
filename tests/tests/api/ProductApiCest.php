<?php


namespace tests\api;


use Faker\Factory;
use ApiTester;

class ProductApiCest
{
     private $authToken;
     private $faker;

     public function _before(ApiTester $I)
     {
          // Initialize Faker
          $this->faker = Factory::create();

          // Authenticate and get the token
          $I->sendPOST(env('API_UNIT_TEST') . 'users/user/login', [
               'name' => 'huysanti123',
               'password' => '123456',
          ]);
          $this->authToken = $I->grabDataFromResponseByJsonPath('$.data.data.access_token')[0];
     }

     public function _after(ApiTester $I)
     {
          // This method is called after each test.
     }

     public function index(ApiTester $I)
     {
          $I->haveHttpHeader('Authorization', 'Bearer ' . $this->authToken);
          $I->sendGET(env('API_UNIT_TEST') . 'shops/product');
          $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
          $I->seeResponseIsJson();
          $I->seeResponseJsonMatchesJsonPath('$.data.products.items[*].id');
          $I->seeResponseJsonMatchesJsonPath('$.data.products.items[*].*');
     }

     public function create(ApiTester $I)
     {
          $I->haveHttpHeader('Authorization', 'Bearer ' . $this->authToken);
          $I->sendPOST(env('API_UNIT_TEST') . 'shops/product/create', [
               'name' => $this->faker->word,
               'description' => $this->faker->sentence,
               'price' => $this->faker->randomFloat(2, 10, 100),
               'slug' => $this->faker->slug,
               'availabel_stock' => $this->faker->randomDigitNotNull,
          ]);
          $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
          $I->seeResponseIsJson();
          $I->seeResponseJsonMatchesJsonPath('$.data.product.*');
     }

     public function view(ApiTester $I)
     {
          $I->haveHttpHeader('Authorization', 'Bearer ' . $this->authToken);
          $productId = $this->faker->randomDigitNotNull;
          $I->sendGET(env('API_UNIT_TEST') . 'shops/product/view/?id=' . $productId);
          $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
          $I->seeResponseIsJson();
          $I->seeResponseJsonMatchesJsonPath('$.data.product.*');
     }

     public function update(ApiTester $I)
     {
          $I->haveHttpHeader('Authorization', 'Bearer ' . $this->authToken);
          $productId = $this->faker->randomDigitNotNull; // Adjust according to your database setup
          $I->sendPUT(env('API_UNIT_TEST') . 'shops/product/update/?id=' . $productId, [
               'name' => $this->faker->word,
               'description' => $this->faker->sentence,
               'price' => $this->faker->randomFloat(2, 10, 100),
               'slug' => $this->faker->slug,
               'availabel_stock' => $this->faker->randomDigitNotNull,
          ]);
          $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
          $I->seeResponseIsJson();
          $I->seeResponseJsonMatchesJsonPath('$.data.product.*');
     }

     public function delete(ApiTester $I)
     {
          $I->haveHttpHeader('Authorization', 'Bearer ' . $this->authToken);
          $productId = 3; // Adjust according to your database setup
          $I->sendDELETE(env('API_UNIT_TEST') . 'shops/product/delete/?id=' . $productId);
          $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
          $I->seeResponseIsJson();
          $I->seeResponseJsonMatchesJsonPath('$.data');

     }

     public function search(ApiTester $I)
     {
          $I->haveHttpHeader('Authorization', 'Bearer ' . $this->authToken);
          $searchQuery = 'sample'; // Adjust according to your needs
          $I->sendGET(env('API_UNIT_TEST') . 'shops/product/search?query=' . $searchQuery);
          $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
          $I->seeResponseIsJson();
          $I->seeResponseContainsJson(['status' => true]);
     }
}

