<?php

namespace PullUpBundle\Service\Training;

use PullUpDomain\Entity\User;
use PullUpDomain\Repository\ExerciseRepositoryInterface;
use PullUpDomain\Repository\GoalRepositoryInterface;
use PullUpDomain\Repository\UserRepositoryInterface;
use PullUpDomain\Service\TrainingPlanManagerInterface;
use PullUpDomain\Entity\Goal;

class TrainingPlanManager implements TrainingPlanManagerInterface
{
    /** @var UserRepositoryInterface */
    protected $userRepository;

    /** @var GoalRepositoryInterface */
    protected $goalRepository;

    /** @var ExerciseRepositoryInterface */
    protected $exerciseRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        GoalRepositoryInterface $goalRepository,
        ExerciseRepositoryInterface $exerciseRepository
    ) {
        $this->userRepository = $userRepository;
        $this->goalRepository = $goalRepository;
        $this->exerciseRepository = $exerciseRepository;
    }

    public function assignUserToPlan(String $plan, User $user): void
    {
        $goalExistings = $this->goalRepository->getListByUser($user);
        if (!empty($goalExistings)) {
            throw new \Exception("User have a goals", 400);
        }

        switch ($plan) {
            case "basic":
                $this->assignBasicPlan($user);
                break;
            
            case "intermediate":
                $this->assignIntermediatePlan($user);
                break;
        }
    }

    protected function assignBasicPlan(User $user): void
    {
        $exerciseId = "273a7542-0b16-4771-9924-1dc53ddc6a47";
        $exercise = $this->exerciseRepository->getByNameOrId($exerciseId);

        $goal = Goal::create("PushUp", "", $user, $exercise);
    }

    protected function assignIntermediatePlan(User $user): void
    {

    }
}
