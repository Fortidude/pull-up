<?php

namespace PullUpService\Handler;

use PullUpDomain\Entity\Goal;
use PullUpDomain\Entity\GoalSet;
use PullUpDomain\Entity\User;

use PullUpDomain\Repository\GoalRepositoryInterface;
use PullUpDomain\Repository\GoalSetRepositoryInterface;

use PullUpService\Command\CreateGoalSetCommand;

class CreateGoalSetHandler
{
    /** @var GoalRepositoryInterface */
    protected $goalRepository;

    /** @var GoalSetRepositoryInterface */
    protected $goalSetRepository;

    /** @var User */
    protected $user;

    private $cachePath;

    public function __construct(
        GoalRepositoryInterface $goalRepository,
        GoalSetRepositoryInterface $exerciseRepository,
        User $user,
        $cachePath = null
    )
    {
        $this->goalRepository = $goalRepository;
        $this->exerciseRepository = $exerciseRepository;
        $this->user = $user;
        $this->cachePath = $cachePath;
    }

    public function handle(CreateGoalSetCommand $command)
    {
        if ($this->cachePath) {
            //file_put_contents($this->cachePath . '/first_form.json', json_encode($data));
        }

        /** @var Goal[] $goals */
        $goals = $this->goalRepository->getListByUser($this->user);
        $goal = null;
        foreach ($goals as $goalEntity) {
            if ($goalEntity->getId() == $command->goal) {
                $goal = $goalEntity;
            }
        }

        if (!$goal) {
            throw new \Exception("Unable to find Goal with ID = \"{$command->goal}\"", 404);
        }

        $dateTime = new \DateTime($command->date);
        $goal->addSet($dateTime, $command->reps, $command->weight, $command->time);
    }
}