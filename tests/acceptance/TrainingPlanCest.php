<?php

class TrainingPlanCest
{
    private $token;

    public function _before(AcceptanceTester $I)
    {
        // test5@test.com is a user with no goals defined
        $I->sendPOST('login_check', ["_username" => "test5@test.com", "_password" => "reksio"]);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), 1);

        $I->assertNotEmpty($response);
        $I->assertArrayHasKey("token", $response);

        $this->token = $response['token'];
    }

    // tests
    public function assignInvalidPlanToUser(AcceptanceTester $I)
    {
        $I->wantTo('assign invalid plan to a user');

        $I->amBearerAuthenticated($this->token);
        $I->sendPOST('secured/training-plan/assign-to-plan/invalid');

        $I->seeResponseCodeIs(400);
        $I->seeResponseEquals('{"code":400,"message":"Invalid plan name","errors":[]}');
    }

    public function assignBasicPlanToUser(AcceptanceTester $I)
    {
        $I->wantTo('assign basic plan to a user');

        $I->amBearerAuthenticated($this->token);
        $I->sendPOST('secured/training-plan/assign-to-plan/basic');

        $I->seeResponseIsJson();
        $I->seeResponseContains('{"status":true}');

        // @TODO VERIFY
    }
}
