<?php 

class FirstCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function apiWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('API');  
    }
}
