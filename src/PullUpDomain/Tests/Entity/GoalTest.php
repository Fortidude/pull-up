<?php

namespace PullUpDomain\Tests\Entity;

use PHPUnit\Framework\TestCase;

use PullUpDomain\Entity\Circuit;
use PullUpDomain\Entity\Goal;
use PullUpDomain\Entity\GoalSet;
use PullUpDomain\Entity\Exercise;
use PullUpDomain\Entity\ExerciseVariant;
use PullUpDomain\Entity\User;

class GoalTest extends TestCase
{
    public function setUp()
    {
    }

    public function testCreateWithException()
    {
        $name = 'test';
        $description = 'test';
        $userMock = $this->createMock(User::class);
        $exerciseMock = $this->createMock(Exercise::class);
        $exerciseVariantMock = $this->createMock(ExerciseVariant::class);

        $this->expectException(\Exception::class);
        Goal::create($name, $description, $userMock, $exerciseMock, $exerciseVariantMock);
    }

    public function testCreate()
    {
        $name = 'test';
        $description = 'test';
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

        $entity = Goal::create($name, $description, $userMock, $exerciseMock, $exerciseVariantMock, $requiredSets);

        $this->assertNull($entity->getLastSetValue());
        $this->assertEquals($requiredSets, $entity->leftThisCircuit());

        $entity->addSet(new \DateTime("now"), 0, 0, 0);

        $this->assertEquals($requiredSets - 1, $entity->leftThisCircuit());
    }

    /**
     * FOR FUTURE USE
     */
    public function testGoalSetWithoutSpecificGoal()
    {
        $userMock = $this->createMock(User::class);

        $reps = 10;
        $time = 100;
        $weight = 1000;

        $exerciseMock = $this->createMock(Exercise::class);
        $exerciseVariantMock = $this->createMock(ExerciseVariant::class);

        $goalEntity = Goal::create(Goal::NO_GOAL_SPECIFIED_NAME, 'empty', $userMock, $exerciseMock, $exerciseVariantMock);

        $entityReps = GoalSet::create($goalEntity, $userMock, new \DateTime(), $reps, 0, 0);
        $this->assertEquals($reps, $entityReps->getValue());

        $entityTime = GoalSet::create($goalEntity, $userMock, new \DateTime(), 0, $time, 0);
        $this->assertEquals($time, $entityTime->getValue());

        $entityWeight = GoalSet::create($goalEntity, $userMock, new \DateTime(), 0, 0, $weight);
        $this->assertEquals($weight, $entityWeight->getValue());
    }
}
