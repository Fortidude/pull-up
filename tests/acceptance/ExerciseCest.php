<?php

class ExerciseCest
{
    private $token;

    public function _before(AcceptanceTester $I)
    {
        $I->sendPOST('login_check', ["_username" => "test2@test.com", "_password" => "reksio"]);
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), 1);

        $I->assertNotEmpty($response);
        $I->assertArrayHasKey("token", $response);

        $this->token = $response['token'];
    }

    public function getExerciseList(AcceptanceTester $I)
    {
        $I->wantTo('get exercise list');

        $I->amBearerAuthenticated($this->token);
        $I->sendGET('secured/exercise/list');
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), true);

        $I->assertTrue($this->isValidUUID($response[0]['id']));
    }

    public function createExerciseList(AcceptanceTester $I)
    {
        $I->wantTo('crete exercise list');

        $exerciseName = "text exercise createtd right now";
        $variantName = "some variant name";
        $isCardio = false;

        /**
         * 
         * CREATE EXERCISE WITH VARIANT
         * 
         */
        $I->amBearerAuthenticated($this->token);
        $I->sendPOST('secured/exercise/create', [
            "name" => $exerciseName,
            "variant_name" => $variantName,
            "is_cardio" => $isCardio,
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContains('{"status":true}');

        /**
         * 
         * fetch exercise list and see if above exercise exist
         * 
         */
        $I->sendGET('secured/exercise/list');
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), true);

        $exerciseFound = false;
        $variantFound = false;
        $isCardio = !$isCardio;
        $exerciseID = null;
        foreach ($response as $key => $exercise) {
            if ($exercise['name'] === $exerciseName) {
                $exerciseFound = true;
                $exerciseID = $exercise['id'];
                $isCardio = $exercise['is_cardio'];

                foreach ($exercise['exercise_variants'] as $variant) {
                    if ($variant['name'] === $variantName) {
                        $variantFound = true;
                    }
                }
            }
        }

        $I->assertTrue($exerciseFound);
        $I->assertTrue($variantFound);
        $I->assertFalse($isCardio);

        $updatedExerciseName = "just the updated name 2";

        /**
         * 
         * UPDATE exercise
         * 
         */
        $I->sendPUT("secured/exercise/{$exerciseID}/update", [
            "name" => $updatedExerciseName,
            "description" => "",
            "is_cardio" => true
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseContains('{"status":true}');

        /**
         * 
         * fetch exercise list and see if above exercise IS UPDATED
         * 
         */
        $I->sendGET('secured/exercise/list');
        $I->seeResponseIsJson();
        $response = json_decode($I->grabResponse(), true);

        $updatedExercise = [];
        foreach ($response as $key => $exercise) {
            if ($exercise['name'] === $updatedExerciseName) {
                $updatedExercise = $exercise;
            }
        }

        $I->assertNotEmpty($updatedExercise);
        $I->assertEquals($updatedExerciseName, $updatedExercise['name']);
        $I->assertTrue($updatedExercise['is_cardio']);
    }

    /**
     *
     */
    private function isValidUUID($uuid)
    {
        $uuid = str_replace(array('urn:', 'uuid:', '{', '}'), '', $uuid);
        $pattern = '^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$';

        if ($uuid == '00000000-0000-0000-0000-000000000000') {
            return true;
        }

        if (!preg_match('/' . $pattern . '/D', $uuid)) {
            return false;
        }

        return true;
    }
}
