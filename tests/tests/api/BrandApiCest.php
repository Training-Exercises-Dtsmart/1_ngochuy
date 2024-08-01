<?php


namespace Api;

use \ApiTester;
use Codeception\Util\HttpCode;
use Faker\Factory;

class BrandApiCest
{
    public function _before(ApiTester $I)
    {
    }


    public function getAllBrands(ApiTester $I)
    {
        $I->sendGET('/brands');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
    }
}
