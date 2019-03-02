<?php

class AuthCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function loginFailedUser(AcceptanceTester $I)
    {
        $I->wantTo('test login FAILED');

        $I->sendPOST('login_check', ["_username" => "test", "_password" => "reksio"]);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), 1);

        $I->assertNotEmpty($response);
        $I->assertArrayHasKey("code", $response);
        $I->assertArrayHasKey("message", $response);
        $I->assertEquals("Bad credentials", $response['message']);
    }

    public function loginSuccessUser(AcceptanceTester $I)
    {
        $I->wantTo('test login SUCCESS');

        $I->sendPOST('login_check', ["_username" => "test2@test.com", "_password" => "reksio"]);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), 1);

        $I->assertNotEmpty($response);
        $I->assertArrayHasKey("token", $response);
    }

    public function registerUser(AcceptanceTester $I)
    {
        $I->wantTo('test register');

        $I->sendPOST('register', [
            "email" => "test200@test.com",
            "username" => "testowy",
            "password" => "reksio",
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContains('{"status":true}');
    }
}
