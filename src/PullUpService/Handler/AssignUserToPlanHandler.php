<?php

namespace PullUpService\Handler;

use PullUpDomain\Entity\Exercise;
use PullUpDomain\Entity\ExerciseVariant;
use PullUpDomain\Entity\Goal;
use PullUpDomain\Entity\User;

use PullUpDomain\Repository\ExerciseRepositoryInterface;

use PullUpService\Command\AssignUserToPlanCommand;
use PullUpDomain\Service\TrainingPlanManagerInterface;

class AssignUserToPlanHandler
{
    /** @var ExerciseRepositoryInterface */
    protected $exerciseRepository;

    /** @var User */
    protected $user;

    /** @var TrainingPlanManagerInterface */
    protected $trainingPlanManager;

    public function __construct(
        User $user,
        TrainingPlanManagerInterface $trainingPlanManager
    )
    {
        $this->user = $user;
        $this->trainingPlanManager = $trainingPlanManager;
    }

    public function handle(AssignUserToPlanCommand $command)
    {
        $this->trainingPlanManager->assignUserToPlan($command->plan, $this->user);
    }
}