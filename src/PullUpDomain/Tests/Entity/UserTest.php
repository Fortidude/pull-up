<?php

namespace PullUpDomain\Tests\Entity;

use PHPUnit\Framework\TestCase;

use PullUpDomain\Entity\Circuit;
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
        $this->assertFalse($user->isTrainingPullUpFirstFormFilled());
    }

    public function testGetCurrentTrainingCircuit()
    {
        $email = "email@test.com";
        $user = User::createUserByFacebook($email, "Jon", 445566);
        
        $currentCircuit = $user->getCurrentTrainingCircuit();

        $this->assertInstanceOf(Circuit::class, $currentCircuit);
    }

    public function testGetTrainingCircuitByDateFinished()
    {
        $email = "email@test.com";
        $user = User::createUserByFacebook($email, "Jon", 445566);

        $date = new \DateTime("now");
        $date->sub(new \DateInterval("P60D"));

        $circuit = $user->getTrainingCircuitByDate($date);

        $this->assertTrue($circuit->isForDate($date));
        $this->assertTrue($circuit->isFinished());
    }

    public function testGetTrainingCircuitByDateNotFinished()
    {
        $email = "email@test.com";
        $user = User::createUserByFacebook($email, "Jon", 445566);
        $daysAgo = $user->getDaysPerCircuit()-2;

        $date = new \DateTime("now");
        $date->sub(new \DateInterval("P{$daysAgo}D"));

        $circuit = $user->getTrainingCircuitByDate($date);

        $this->assertTrue($circuit->isForDate($date));
        $this->assertFalse($circuit->isFinished());
    }

    public function testSetTrainingCircuitFinished()
    {
        $email = "email@test.com";
        $circuitDays = 10;
        $user = User::createUserByFacebook($email, "Jon", 445566);
        $user->changeDaysAmountPerCircuit($circuitDays);

        $start = new \DateTime("now");
        $endWeek = (clone $start)->add(new \DateInterval("P7D"));

        $circuit = $user->getCurrentTrainingCircuit();

        $format = 'Y-m-d';

        $this->assertTrue($circuit->isCurrent());
        $this->assertEquals($start->format($format), $circuit->getStartAt()->format($format));
        $this->assertEquals($endWeek->format($format), $circuit->getEndAt()->format($format));

        $customDate = (clone $start)->add(new \DateInterval("P{$circuitDays}D"));
        $nextCircuit = $user->getTrainingCircuitByDate($customDate);

        $this->assertFalse($nextCircuit->isCurrent());
        $this->assertFalse($nextCircuit->isFinished());
        $this->assertEquals($circuitDays, $nextCircuit->getDuration());
        $this->assertEquals($customDate->format($format), $nextCircuit->getStartAt()->format($format));
    }
}
