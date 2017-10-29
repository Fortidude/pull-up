<?php

namespace PullUpDomain\Tests\Entity;

use PHPUnit\Framework\TestCase;

use PullUpDomain\Entity\User;

class UserTest extends TestCase
{
    public function setUp()
    {
    }

    public function testCreate()
    {
        $email = "email@test.com";
        $user = User::createUserByFacebook($email, "Jon", 445566);

        $this->assertEquals($email, $user->getEmail());
        $this->assertTrue($user->isEnabled());
        $this->assertFalse($user->isFirstFormFilled());
    }

    public function getGetCurrentTrainingCircuit()
    {
        $email = "email@test.com";
        $user = User::createUserByFacebook($email, "Jon", 445566);

      //  $currentCircuit = $user->getCurrentTrainingCircuit();

      //  $now = new \DateTime("now");
       // $now
      //  $expected = [
      //      'started' =>
       // ];
    }
}
