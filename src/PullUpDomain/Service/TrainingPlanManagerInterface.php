<?php

namespace PullUpDomain\Service;

use PullUpDomain\Entity\User;
use PullUpDomain\Repository\ExerciseRepositoryInterface;
use PullUpDomain\Repository\GoalRepositoryInterface;
use PullUpDomain\Repository\UserRepositoryInterface;

interface TrainingPlanManagerInterface
{
    public function __construct(
        UserRepositoryInterface $userRepository,
        GoalRepositoryInterface $goalRepository,
        ExerciseRepositoryInterface $exerciseRepository
    );

    public function assignUserToPlan(String $plan, User $user): void;
}
