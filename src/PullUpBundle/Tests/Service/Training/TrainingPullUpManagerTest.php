<?php

namespace PullUpBundle\Tests\Service\Training;

use PHPUnit\Framework\TestCase;

use PullUpBundle\Repository\TrainingPullUpRepository;
use PullUpBundle\Service\Training\TrainingPullUpManager;

use PullUpDomain\Data\TrainingPullUp as TrainingPullUpData;
use PullUpDomain\Entity\TrainingPullUp;
use PullUpDomain\Entity\User;

class TrainingPullUpManagerTest extends TestCase
{
    public function setUp()
    {
    }

    public function testGetCurrentWhenNewUser()
    {
        $maxRepNumberForTest = 10;
        $trainingService = new TrainingPullUpData();
        $user = User::createUserByFacebook('test@email.com', 'tester', 1234);

        $repository = $this->createMock(TrainingPullUpRepository::class);
        $repository->expects($this->any())
            ->method('getLastDone')
            ->willReturn(null);

        $trainingManager = new TrainingPullUpManager($repository);

        $current = $trainingManager->getCurrent($user);
        $this->assertEquals($trainingService->getFirst(), $current);
        $this->assertNotEquals($trainingService->getSecond($maxRepNumberForTest), $current);
    }

    public function testGetCurrentWhenSomeTrainingsAreDone()
    {
        $trainingService = new TrainingPullUpData();
        $user = User::createUserByFacebook('test@email.com', 'tester', 1234);

        $trainingPullUpEntity = TrainingPullUp::create($user, 2, 'three', 1, 10);

        $repository = $this->createMock(TrainingPullUpRepository::class);
        $repository->expects($this->any())
            ->method('getLastDone')
            ->willReturn($trainingPullUpEntity);

        $trainingManager = new TrainingPullUpManager($repository);
        $trainingManager->setIntervalBetweenTraining(false);

        $current = $trainingManager->getCurrent($user);

        $this->assertEquals($trainingService->getFourth($trainingPullUpEntity->getReps()), $current);
        $this->assertNotEquals($trainingService->getFirst(), $current);
    }

    public function testGetGetCurrentWhenItsLastOne()
    {
        $trainingService = new TrainingPullUpData();
        $user = User::createUserByFacebook('test@email.com', 'tester', 1234);

        $tcEntityOne = TrainingPullUp::create($user, 2, 'one', 1, 10);
        $tcEntityTwo = TrainingPullUp::create($user, 2, 'two', 2, 10);
        $tcEntityThree = TrainingPullUp::create($user, 2, 'three', 3, 10); // the harder by level == 3
        $tcEntityFour = TrainingPullUp::create($user, 2, 'four', 1, 10);

        $repository = $this->createMock(TrainingPullUpRepository::class);
        $repository->expects($this->any())
            ->method('getLastDone')
            ->willReturn($tcEntityFour);
        $repository->expects($this->any())
            ->method('getListByUserAndRoute')
            ->willReturn([$tcEntityOne, $tcEntityTwo, $tcEntityThree, $tcEntityFour]);

        $trainingManager = new TrainingPullUpManager($repository);
        $trainingManager->setIntervalBetweenTraining(false);

        $current = $trainingManager->getCurrent($user);

        $this->assertEquals($trainingService->getThird($tcEntityOne->getReps()), $current);
        $this->assertNotEquals($trainingService->getFirst(), $current);
    }


    public function testGetGetCurrentWhenIntervalIsUsed()
    {
        $interval = new \DateInterval("PT50H");

        $trainingService = new TrainingPullUpData();
        $user = User::createUserByFacebook('test@email.com', 'tester', 1234);

        $tcEntityOne = TrainingPullUp::create($user, 2, 'one', 1, 10);
        $tcEntityTwo = TrainingPullUp::create($user, 2, 'two', 2, 10);
        $tcEntityThree = TrainingPullUp::create($user, 2, 'three', 3, 10);

        $repository = $this->createMock(TrainingPullUpRepository::class);
        $repository->expects($this->any())
            ->method('getLastDone')
            ->willReturn($tcEntityThree);
        $repository->expects($this->any())
            ->method('getListByUserAndRoute')
            ->willReturn([$tcEntityOne, $tcEntityTwo, $tcEntityThree]);

        $trainingManager = new TrainingPullUpManager($repository);
        $trainingManager->setIntervalBetweenTraining($interval);

        $current = $trainingManager->getCurrent($user);

        $expectedCurrent = $trainingService->getNextIsNotAvailableYetCauseOfInterval($tcEntityOne->getCreatedAt(), $interval);

        $this->assertEquals($expectedCurrent, $current);
        $this->assertNotEquals($trainingService->getFirst(), $current);
    }
}
