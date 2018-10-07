<?php

namespace PullUpDomain\Tests\Entity;

use PHPUnit\Framework\TestCase;

use PullUpDomain\Entity\Circuit;
use PullUpDomain\Entity\Goal;
use PullUpDomain\Entity\GoalSet;
use PullUpDomain\Entity\Exercise;
use PullUpDomain\Entity\ExerciseVariant;
use PullUpDomain\Entity\Section;
use PullUpDomain\Entity\User;

class SectionTest extends TestCase
{
    public function setUp()
    {
    }

    public function testCreate()
    {
        $userMock = $this->createMock(User::class);
        $exerciseMock = $this->createMock(Exercise::class);
        $exerciseVariantMock = $this->createMock(ExerciseVariant::class);

        $circuitMock = Circuit::create($userMock);
        $userMock->expects($this->any())
            ->method('getTrainingCircuitByDate')
            ->willReturn($circuitMock);
        $userMock->expects($this->any())
            ->method('getCurrentTrainingCircuit')
            ->willReturn($circuitMock);

        $requiredSets = 10;

        $goalOne = Goal::create('goal one', 'desc one', $userMock, $exerciseMock, $exerciseVariantMock, $requiredSets);
        $goalTwo = Goal::create('goal two', 'desc two', $userMock, $exerciseMock, $exerciseVariantMock, $requiredSets);

        $entity = Section::create('section one', 'desc section one', $userMock, [$goalOne, $goalTwo]);

        $this->assertEquals('section one', $entity->getName());
        $this->assertEquals('desc section one', $entity->getDescription());
        $this->assertEquals($userMock, $entity->getUser());
        $this->assertContainsOnlyInstancesOf(Goal::class, $entity->getGoals());
    }
}
