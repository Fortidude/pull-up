<?php

namespace PullUpDomain\Tests\Entity;

use PHPUnit\Framework\TestCase;

use PullUpDomain\Entity\TrainingPullUp;
use PullUpDomain\Entity\User;

class TrainingPullUpTest extends TestCase
{
    public function setUp()
    {
    }

    public function testCreate()
    {
        $interval = new \DateInterval('PT1S');
        $user = $this->createMock(User::class);

        $entity = TrainingPullUp::create($user, 1, 'three', 2, 10);

        $this->assertFalse($entity->isNextAvailable($interval));

        sleep(1);

        $this->assertTrue($entity->isNextAvailable($interval));
    }
}
