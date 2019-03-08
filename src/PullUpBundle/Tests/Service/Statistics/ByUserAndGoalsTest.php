<?php

namespace PullUpBundle\Tests\Service\Statistics;

use PHPUnit\Framework\TestCase;
use PullUpBundle\Service\Statistics\ByUserAndGoals;
use PullUpDomain\Entity\Exercise;
use PullUpDomain\Entity\User;

class ByUserAndGoalsTest extends TestCase
{
    public function setUp()
    {
    }

    /**
     * Current Circuit
     * Reps
     * should be 100 percent
     */
    public function testByCurrentCircuitWithReps100Percent()
    {
        $requiredReps = 50;

        $now = new \DateTime();

        $user = User::createByClassicRegister('test@test.com', 'test_user', 'password1234');
        $user->getTrainingCircuitByDate($now);

        $exerciseOne = Exercise::create('name_exercise_one', '');
        $exerciseOne->addExerciseVariant('variant_one', '');
        $exerciseOne->addExerciseVariant('variant_two', '');

        $goal = MockGoal::create('goal_1', '', $user, $exerciseOne, null, null, $requiredReps);
        $goal->setCreatedAt($now);
        $goal->addSet($now, 2, 10);
        $goal->addSet($now, 2, 10);
        $goal->addSet($now, 2, 30);

        $service = new ByUserAndGoals();
        $statistics = $service->get($user, [$goal]);

        $this->assertEquals(1, $statistics->currentCirclePercentageGoalsAchieved['total_circuits']);
        $this->assertEquals(1, $statistics->currentCirclePercentageGoalsAchieved['total_goals']);
        $this->assertEquals(100, $statistics->currentCirclePercentageGoalsAchieved['goals'][0]['percentage']);
        $this->assertEquals(100, $statistics->currentCirclePercentGoalsAchieved);
        $this->assertEquals($goal->getExerciseVariantName(), $statistics->currentCirclePercentageGoalsAchieved['goals'][0]['variant_name']);
        $this->assertEquals($goal->getExerciseName(), $statistics->currentCirclePercentageGoalsAchieved['goals'][0]['name']);
    }

    /**
     * Current Circuit
     * Reps
     * should be less than 100 percent
     */
    public function testByCurrentCircuitWithRepsLessThan100Percent()
    {
        $requiredReps = 50;

        $now = new \DateTime();

        $user = User::createByClassicRegister('test@test.com', 'test_user', 'password1234');
        $user->getTrainingCircuitByDate($now);

        $exerciseOne = Exercise::create('name_exercise_one', '');
        $exerciseOne->addExerciseVariant('variant_one', '');
        $exerciseOne->addExerciseVariant('variant_two', '');

        $goal = MockGoal::create('goal_1', '', $user, $exerciseOne, null, null, $requiredReps);
        $goal->setCreatedAt($now);
        $goal->addSet($now, 2, 10);
        $goal->addSet($now, 2, 10);

        $service = new ByUserAndGoals();
        $statistics = $service->get($user, [$goal]);

        $this->assertEquals(1, $statistics->currentCirclePercentageGoalsAchieved['total_circuits']);
        $this->assertEquals(1, $statistics->currentCirclePercentageGoalsAchieved['total_goals']);
        $this->assertEquals(40, $statistics->currentCirclePercentageGoalsAchieved['goals'][0]['percentage']);
        $this->assertEquals(40, $statistics->currentCirclePercentGoalsAchieved);
        $this->assertEquals($goal->getExerciseVariantName(), $statistics->currentCirclePercentageGoalsAchieved['goals'][0]['variant_name']);
        $this->assertEquals($goal->getExerciseName(), $statistics->currentCirclePercentageGoalsAchieved['goals'][0]['name']);
    }

    /**
     * Current Circuit
     * Sets
     * should be 100 percent
     */
    public function testByCurrentCircuitWithSets100Percent()
    {
        $requiredSets = 2;

        $now = new \DateTime();

        $user = User::createByClassicRegister('test@test.com', 'test_user', 'password1234');
        $user->getTrainingCircuitByDate($now);

        $exerciseOne = Exercise::create('name_exercise_one', '');
        $exerciseOne->addExerciseVariant('variant_one', '');
        $exerciseOne->addExerciseVariant('variant_two', '');

        $goal = MockGoal::create('goal_1', '', $user, $exerciseOne, null, $requiredSets);
        $goal->setCreatedAt($now);
        $goal->addSet($now, 2, 2);
        $goal->addSet($now, 2, 2);

        $service = new ByUserAndGoals();
        $statistics = $service->get($user, [$goal]);

        $this->assertEquals(1, $statistics->currentCirclePercentageGoalsAchieved['total_circuits']);
        $this->assertEquals(1, $statistics->currentCirclePercentageGoalsAchieved['total_goals']);
        $this->assertEquals(100, $statistics->currentCirclePercentageGoalsAchieved['goals'][0]['percentage']);
        $this->assertEquals(100, $statistics->currentCirclePercentGoalsAchieved);
        $this->assertEquals($goal->getExerciseVariantName(), $statistics->currentCirclePercentageGoalsAchieved['goals'][0]['variant_name']);
        $this->assertEquals($goal->getExerciseName(), $statistics->currentCirclePercentageGoalsAchieved['goals'][0]['name']);
    }

    /**
     * Current Circuit
     * Sets
     * should be less than 100 percent
     */
    public function testByCurrentCircuitWithSetsLessThan100Percent()
    {
        $requiredSets = 4;

        $now = new \DateTime();

        $user = User::createByClassicRegister('test@test.com', 'test_user', 'password1234');
        $user->getTrainingCircuitByDate($now);

        $exerciseOne = Exercise::create('name_exercise_one', '');
        $exerciseOne->addExerciseVariant('variant_one', '');
        $exerciseOne->addExerciseVariant('variant_two', '');

        $goal = MockGoal::create('goal_1', '', $user, $exerciseOne, null, $requiredSets);
        $goal->setCreatedAt($now);
        $goal->addSet($now);
        $goal->addSet($now);

        $service = new ByUserAndGoals();
        $statistics = $service->get($user, [$goal]);

        $this->assertEquals(1, $statistics->currentCirclePercentageGoalsAchieved['total_circuits']);
        $this->assertEquals(1, $statistics->currentCirclePercentageGoalsAchieved['total_goals']);
        $this->assertEquals(50, $statistics->currentCirclePercentageGoalsAchieved['goals'][0]['percentage']);
        $this->assertEquals(50, $statistics->currentCirclePercentGoalsAchieved);
        $this->assertEquals($goal->getExerciseVariantName(), $statistics->currentCirclePercentageGoalsAchieved['goals'][0]['variant_name']);
        $this->assertEquals($goal->getExerciseName(), $statistics->currentCirclePercentageGoalsAchieved['goals'][0]['name']);
    }

    /**
     * Current Circuit
     * Time
     * should be 100 percent
     */
    public function testByCurrentCircuitWithTime100Percent()
    {
        $requiredTime = 200;

        $now = new \DateTime();

        $user = User::createByClassicRegister('test@test.com', 'test_user', 'password1234');
        $user->getTrainingCircuitByDate($now);

        $exerciseOne = Exercise::create('name_exercise_one', '');
        $exerciseOne->addExerciseVariant('variant_one', '');
        $exerciseOne->addExerciseVariant('variant_two', '');

        $goal = MockGoal::create('goal_1', '', $user, $exerciseOne, null, null, null, null, $requiredTime);
        $goal->setCreatedAt($now);
        $goal->addSet($now, 2, 80);
        $goal->addSet($now, 2, 120);

        $service = new ByUserAndGoals();
        $statistics = $service->get($user, [$goal]);

        $this->assertEquals(1, $statistics->currentCirclePercentageGoalsAchieved['total_circuits']);
        $this->assertEquals(1, $statistics->currentCirclePercentageGoalsAchieved['total_goals']);
        $this->assertEquals(100, $statistics->currentCirclePercentageGoalsAchieved['goals'][0]['percentage']);
        $this->assertEquals(100, $statistics->currentCirclePercentGoalsAchieved);
        $this->assertEquals($goal->getExerciseVariantName(), $statistics->currentCirclePercentageGoalsAchieved['goals'][0]['variant_name']);
        $this->assertEquals($goal->getExerciseName(), $statistics->currentCirclePercentageGoalsAchieved['goals'][0]['name']);
    }

    /**
     * Current Circuit
     * Time
     * should be less than 100 percent
     */
    public function testByCurrentCircuitWithTimeLessThan100Percent()
    {
        $requiredTime = 160;

        $now = new \DateTime();

        $user = User::createByClassicRegister('test@test.com', 'test_user', 'password1234');
        $user->getTrainingCircuitByDate($now);

        $exerciseOne = Exercise::create('name_exercise_one', '');
        $exerciseOne->addExerciseVariant('variant_one', '');
        $exerciseOne->addExerciseVariant('variant_two', '');

        $goal = MockGoal::create('goal_1', '', $user, $exerciseOne, null, null, null, null, $requiredTime);
        $goal->setCreatedAt($now);
        $goal->addSet($now, 2, 32);

        $service = new ByUserAndGoals();
        $statistics = $service->get($user, [$goal]);

        $this->assertEquals(1, $statistics->currentCirclePercentageGoalsAchieved['total_circuits']);
        $this->assertEquals(1, $statistics->currentCirclePercentageGoalsAchieved['total_goals']);
        $this->assertEquals(20, $statistics->currentCirclePercentageGoalsAchieved['goals'][0]['percentage']);
        $this->assertEquals(20, $statistics->currentCirclePercentGoalsAchieved);
        $this->assertEquals($goal->getExerciseVariantName(), $statistics->currentCirclePercentageGoalsAchieved['goals'][0]['variant_name']);
        $this->assertEquals($goal->getExerciseName(), $statistics->currentCirclePercentageGoalsAchieved['goals'][0]['name']);
    }

    /**
     * Last Circuit
     * Reps
     * should be 100 percent
     */
    public function testByLastCircuitWithReps100Percent()
    {
        $requiredReps = 10;

        $twoDaysAgo = new \DateTime();
        $twoDaysAgo->sub(new \DateInterval('P2D'));

        $user = User::createByClassicRegister('test@test.com', 'test_user', 'password1234');
        $user->changeDaysAmountPerCircuit(4);
        $user->getTrainingCircuitByDate(new \DateTime());
        $user->getTrainingCircuitByDate($twoDaysAgo);

        $exerciseOne = Exercise::create('name_exercise_one', '');
        $exerciseOne->addExerciseVariant('variant_one', '');
        $exerciseOne->addExerciseVariant('variant_two', '');

        $goal = MockGoal::create('goal_1', '', $user, $exerciseOne, null, null, $requiredReps);
        $goal->setCreatedAt($twoDaysAgo);
        $goal->addSet($twoDaysAgo, 2, 4);
        $goal->addSet($twoDaysAgo, 2, 6);

        $service = new ByUserAndGoals();
        $statistics = $service->get($user, [$goal]);

        $this->assertEquals(1, $statistics->lastCirclePercentageGoalsAchieved['total_circuits']);
        $this->assertEquals(1, $statistics->lastCirclePercentageGoalsAchieved['total_goals']);
        $this->assertEquals(100, $statistics->lastCirclePercentageGoalsAchieved['goals'][0]['percentage']);
        $this->assertEquals(100, $statistics->lastCirclePercentGoalsAchieved);
        $this->assertEquals($goal->getExerciseVariantName(), $statistics->lastCirclePercentageGoalsAchieved['goals'][0]['variant_name']);
        $this->assertEquals($goal->getExerciseName(), $statistics->lastCirclePercentageGoalsAchieved['goals'][0]['name']);
    }

    /**
     * Last Circuit
     * Reps
     * should be less than 100 percent
     */
    public function testByLastCircuitWithRepsLessThan100Percent()
    {
        $requiredReps = 10;

        $fiveDaysAgo = new \DateTime();
        $fiveDaysAgo->sub(new \DateInterval('P2D'));

        $user = User::createByClassicRegister('test@test.com', 'test_user', 'password1234');
        $user->changeDaysAmountPerCircuit(4);

        $exerciseOne = Exercise::create('name_exercise_one', '');
        $exerciseOne->addExerciseVariant('variant_one', '');
        $exerciseOne->addExerciseVariant('variant_two', '');

        $goal = MockGoal::create('goal_1', '', $user, $exerciseOne, null, null, $requiredReps);
        $goal->setCreatedAt($fiveDaysAgo);
        $goal->addSet($fiveDaysAgo, 2, 4);

        $service = new ByUserAndGoals();
        $statistics = $service->get($user, [$goal]);

        $this->assertEquals(1, $statistics->lastCirclePercentageGoalsAchieved['total_circuits']);
        $this->assertEquals(1, $statistics->lastCirclePercentageGoalsAchieved['total_goals']);
        $this->assertEquals(40, $statistics->lastCirclePercentageGoalsAchieved['goals'][0]['percentage']);
        $this->assertEquals(40, $statistics->lastCirclePercentGoalsAchieved);
        $this->assertEquals($goal->getExerciseVariantName(), $statistics->lastCirclePercentageGoalsAchieved['goals'][0]['variant_name']);
        $this->assertEquals($goal->getExerciseName(), $statistics->lastCirclePercentageGoalsAchieved['goals'][0]['name']);
    }

    public function testByCircuitsAndMultipleGoals()
    {
        $user = User::createByClassicRegister('test@test.com', 'test_user', 'password1234');
        $user->changeDaysAmountPerCircuit(4);

        $now = new \DateTime();
        $user->getTrainingCircuitByDate($now);

        $fiveDaysAgo = clone $now;
        $fiveDaysAgo->sub(new \DateInterval('P5D'));
        $user->getTrainingCircuitByDate($fiveDaysAgo);

        $tenDaysAgo = clone $now;
        $tenDaysAgo->sub(new \DateInterval('P10D'));
        $user->getTrainingCircuitByDate($tenDaysAgo);

        $fifteenDaysAgo = clone $now;
        $fifteenDaysAgo->sub(new \DateInterval('P15D'));
        $user->getTrainingCircuitByDate($fifteenDaysAgo);

        $exerciseOne = Exercise::create('name_exercise_one', '');
        $exerciseOne->addExerciseVariant('variant_one', '');

        $exerciseTwo = Exercise::create('name_exercise_two', '');
        $exerciseTwo->addExerciseVariant('variant_two', '');

        $goalOne = MockGoal::create('goal_1', '', $user, $exerciseOne, null, null, 10);
        $goalOne->setCreatedAt($now);
        $goalOne->addSet($now, 2, 2);

        $goalTwo = MockGoal::create('goal_2', '', $user, $exerciseOne, $exerciseOne->getExerciseVariants()[0], null, 10);
        $goalTwo->setCreatedAt($fiveDaysAgo);
        $goalTwo->addSet($fiveDaysAgo, 2, 10);

        $goalThree = MockGoal::create('goal_3', '', $user, $exerciseTwo, null, null, 10);
        $goalThree->setCreatedAt($tenDaysAgo);
        $goalThree->addSet($tenDaysAgo, 2, 10);

        $goalFour = MockGoal::create('goal_4', '', $user, $exerciseTwo, $exerciseTwo->getExerciseVariants()[0], null, 10);
        $goalFour->setCreatedAt($fifteenDaysAgo);
        $goalFour->addSet($fifteenDaysAgo, 2, 10);

        $service = new ByUserAndGoals();
        $statistics = $service->get($user, [$goalOne, $goalTwo, $goalThree, $goalFour]);

        $this->assertEquals(4, $statistics->percentageGoalsAchieved['total_goals']);
        $this->assertEquals(3, $statistics->percentageGoalsAchieved['total_goals_achieved']);
        $this->assertEquals(4, $statistics->percentageGoalsAchieved['total_circuits']);

        $this->assertEquals(5, $statistics->currentCirclePercentGoalsAchieved);
        $this->assertEquals(25, $statistics->lastCirclePercentGoalsAchieved);
        $this->assertEquals(32, $statistics->percentGoalsAchieved);

        $this->assertEquals($goalOne->getExerciseName(), $statistics->percentageGoalsAchieved['goals'][0]['name']);
        $this->assertEquals($goalTwo->getExerciseName(), $statistics->percentageGoalsAchieved['goals'][1]['name']);
        $this->assertEquals($goalThree->getExerciseName(), $statistics->percentageGoalsAchieved['goals'][2]['name']);
        $this->assertEquals($goalFour->getExerciseName(), $statistics->percentageGoalsAchieved['goals'][3]['name']);

        $this->assertEquals(20, $statistics->percentageGoalsAchieved['goals'][0]['percentage']);
        $this->assertEquals(50, $statistics->percentageGoalsAchieved['goals'][1]['percentage']);
        $this->assertEquals(33, $statistics->percentageGoalsAchieved['goals'][2]['percentage']);
        $this->assertEquals(25, $statistics->percentageGoalsAchieved['goals'][3]['percentage']);
    }

    public function testByCircuitsAndMultipleGoalsForTheSameExercise()
    {
        $user = User::createByClassicRegister('test@test.com', 'test_user', 'password1234');
        $user->changeDaysAmountPerCircuit(4);

        $now = new \DateTime();
        $user->getTrainingCircuitByDate($now);

        $tenDaysAgo = clone $now;
        $tenDaysAgo->sub(new \DateInterval('P10D'));

        $exerciseOne = Exercise::create('name_exercise_one', '');
        $exerciseOne->addExerciseVariant('variant_one', '');

        $exerciseTwo = Exercise::create('name_exercise_two', '');
        $exerciseTwo->addExerciseVariant('variant_two', '');

        $goalOne = MockGoal::create('goal_1', '', $user, $exerciseOne, null, 3);
        $goalOne->setCreatedAt($tenDaysAgo);
        $goalOne->addSet($now, 2, 3);

        $goalTwo = MockGoal::create('goal_2', '', $user, $exerciseTwo, $exerciseTwo->getExerciseVariants()[0], null, 10);
        $goalTwo->setCreatedAt($tenDaysAgo);
        $goalTwo->addSet($now, 2, 1);

        $goalThree = MockGoal::create('goal_3', '', $user, $exerciseOne, null, null, 10);
        $goalThree->setCreatedAt($tenDaysAgo);
        $goalThree->addSet($now, 2, 4);

        $goalFour = MockGoal::create('goal_4', '', $user, $exerciseOne, null, 10);
        $goalFour->setCreatedAt($tenDaysAgo);
        $goalFour->addSet($now, 2, 4);
        $goalFour->addSet($now, 2, 40);

        $service = new ByUserAndGoals();
        $statistics = $service->get($user, [$goalOne, $goalTwo, $goalThree, $goalFour]);

        $this->assertEquals(4, $statistics->currentCirclePercentageGoalsAchieved['total_goals']);
        $this->assertEquals(0, $statistics->currentCirclePercentageGoalsAchieved['total_goals_achieved']);
        $this->assertEquals(33, $statistics->currentCirclePercentageGoalsAchieved['goals'][0]['percentage']);
        $this->assertEquals(10, $statistics->currentCirclePercentageGoalsAchieved['goals'][1]['percentage']);
        $this->assertEquals(40, $statistics->currentCirclePercentageGoalsAchieved['goals'][2]['percentage']);
        $this->assertEquals(20, $statistics->currentCirclePercentageGoalsAchieved['goals'][3]['percentage']);
    }

    /**
     * @throws \Exception
     *
     * @TODO BROKEN TEST
     */
    public function testByCircuitsAndMultipleGoalsAchieved100Percent()
    {
        $user = User::createByClassicRegister('test@test.com', 'test_user', 'password1234');
        $user->changeDaysAmountPerCircuit(4);

        $now = new \DateTime();
        $user->getTrainingCircuitByDate($now);

        $twoDaysAgo = clone $now;
        $twoDaysAgo->sub(new \DateInterval('P2D'));
        $user->getTrainingCircuitByDate($twoDaysAgo);

        $sixDaysAgo = clone $now;
        $sixDaysAgo->sub(new \DateInterval('P6D'));
        $user->getTrainingCircuitByDate($sixDaysAgo);

        $tenDaysAgo = clone $now;
        $tenDaysAgo->sub(new \DateInterval('P10D'));
        $user->getTrainingCircuitByDate($tenDaysAgo);

        $exerciseOne = Exercise::create('name_exercise_one', '');
        $exerciseOne->addExerciseVariant('variant_one', '');

        $exerciseTwo = Exercise::create('name_exercise_two', '');
        $exerciseTwo->addExerciseVariant('variant_two', '');

        $goalOne = MockGoal::create('goal_1', '', $user, $exerciseOne, null, null, 20);
        $goalOne->setCreatedAt($tenDaysAgo);
        $goalOne->addSet($now, 2, 4);
        $goalOne->addSet($twoDaysAgo, 2, 3);
        $goalOne->addSet($sixDaysAgo, 2, 2);
        $goalOne->addSet($tenDaysAgo, 2, 1);

        $goalTwo = MockGoal::create('goal_2', '', $user, $exerciseOne, $exerciseOne->getExerciseVariants()[0], null, 10);
        $goalTwo->setCreatedAt($tenDaysAgo);
        $goalTwo->addSet($now, 2, 10);
        $goalTwo->addSet($twoDaysAgo, 2, 10);
        $goalTwo->addSet($sixDaysAgo, 2, 2, 10);
        $goalTwo->addSet($tenDaysAgo, 2, 10);

        $service = new ByUserAndGoals();
        $statistics = $service->get($user, [$goalOne, $goalTwo]);

        $this->assertEquals(2, $statistics->percentageGoalsAchieved['total_goals']);
        $this->assertEquals(4, $statistics->percentageGoalsAchieved['total_circuits']);

        $this->assertEquals(60, $statistics->currentCirclePercentGoalsAchieved);
        $this->assertEquals(57, $statistics->lastCirclePercentGoalsAchieved);
        $this->assertEquals(46, $statistics->percentGoalsAchieved);

        $this->assertEquals($goalOne->getExerciseName(), $statistics->percentageGoalsAchieved['goals'][0]['name']);
        $this->assertEquals($goalTwo->getExerciseName(), $statistics->percentageGoalsAchieved['goals'][1]['name']);

        $this->assertEquals(12, $statistics->percentageGoalsAchieved['goals'][0]['percentage']);
        $this->assertEquals(80, $statistics->percentageGoalsAchieved['goals'][1]['percentage']);
    }
}
