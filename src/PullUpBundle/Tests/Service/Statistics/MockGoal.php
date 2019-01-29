<?php

namespace PullUpBundle\Tests\Service\Statistics;

use PullUpDomain\Entity\Exercise;
use PullUpDomain\Entity\ExerciseVariant;
use PullUpDomain\Entity\Goal;
use PullUpDomain\Entity\User;

class MockGoal extends Goal
{
    /**
     * @param string $name
     * @param string $description
     * @param User $user
     * @param Exercise $exercise
     * @param ExerciseVariant $exerciseVariant
     * @param int|null $requiredSets
     * @param int|null $requiredReps
     * @param int|null $requiredWeight
     * @param int|null $requiredTime
     * @return MockGoal
     * @throws \Exception
     */
    public static function create(
        string $name,
        string $description,
        User $user,
        Exercise $exercise,
        ExerciseVariant $exerciseVariant = null,
        int $requiredSets = null,
        int $requiredReps = null,
        int $requiredWeight = null,
        int $requiredTime = null
    ): MockGoal
    {
        if (!$requiredSets && !$requiredReps && !$requiredWeight && !$requiredTime && $name !== self::NO_GOAL_SPECIFIED_NAME) {
            throw new \Exception("none type were selected");
        }

        $entity = new self();

        $entity->noSpecifiedGoal = false;
        if ($name === self::NO_GOAL_SPECIFIED_NAME) {
            $entity->noSpecifiedGoal = true;
        }

        $entity->name = $name;
        $entity->description = $description;

        $entity->user = $user;

        $entity->exercise = $exercise;
        $entity->exerciseVariant = $exerciseVariant;

        $entity->requiredSets = $requiredSets;
        $entity->requiredReps = $requiredReps;
        $entity->requiredWeight = $requiredWeight;
        $entity->requiredTime = $requiredTime;

        $entity->removed = false;

        $entity->lastSetAdded = new \DateTime("now");

        return $entity;
    }

    public function getId()
    {
        return $this->name . $this->getExerciseName();
    }

    public function setCreatedAt(\DateTime $dateTime)
    {
        $this->createdAt = $dateTime;
    }
}