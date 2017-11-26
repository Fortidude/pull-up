<?php

namespace PullUpService\Handler;

use PullUpDomain\Entity\Goal;
use PullUpDomain\Entity\GoalSet;
use PullUpDomain\Entity\User;

use PullUpDomain\Repository\GoalRepositoryInterface;
use PullUpDomain\Repository\GoalSetRepositoryInterface;

use PullUpService\Command\CreateGoalSetCommand;
use PullUpService\Event\GoalSetCreatedEvent;

class CreateGoalSetHandler
{
    /** @var GoalRepositoryInterface */
    protected $goalRepository;

    /** @var GoalSetRepositoryInterface */
    protected $goalSetRepository;

    /** @var User */
    protected $user;

    protected $eventBus;

    private $cachePath;

    public function __construct(
        GoalRepositoryInterface $goalRepository,
        GoalSetRepositoryInterface $goalSetRepository,
        User $user,
        $eventBus,
        $cachePath = null
    )
    {
        $this->goalRepository = $goalRepository;
        $this->goalSetRepository = $goalSetRepository;
        $this->user = $user;
        $this->eventBus = $eventBus;
        $this->cachePath = $cachePath;
    }

    public function handle(CreateGoalSetCommand $command)
    {
        if ($this->cachePath) {
            //file_put_contents($this->cachePath . '/first_form.json', json_encode($data));
        }

        if (!$command->goal) {

        }

        /** @var Goal $goal */
        $goal = $this->goalRepository->getByUserAndId($this->user, $command->goal);
        if (!$goal) {
            throw new \Exception("Unable to find Goal with ID = \"{$command->goal}\"", 404);
        }

        $dateTime = new \DateTime($command->date);
        $set = $goal->addSet($dateTime, $command->reps, $command->weight, $command->time);

        $this->goalSetRepository->add($set);

        $event = new GoalSetCreatedEvent($set->getId());
        $this->eventBus->handle($event);
    }
}