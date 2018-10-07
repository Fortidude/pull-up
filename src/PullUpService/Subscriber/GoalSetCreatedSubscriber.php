<?php

namespace PullUpService\Subscriber;

use PullUpDomain\Repository\CircuitRepositoryInterface;
use PullUpDomain\Repository\GoalSetRepositoryInterface;

use PullUpDomain\Repository\UserRepositoryInterface;
use PullUpService\Event\GoalSetCreatedEvent;

class GoalSetCreatedSubscriber
{
    protected $goalSetRepository;
    protected $circuitRepository;
    protected $userRepository;
    protected $cachePath;

    public function __construct(
        GoalSetRepositoryInterface $goalSetRepository,
        CircuitRepositoryInterface $circuitRepository,
        UserRepositoryInterface $userRepository,
        $cachePath
    )
    {
        $this->goalSetRepository = $goalSetRepository;
        $this->circuitRepository = $circuitRepository;
        $this->userRepository = $userRepository;
        $this->cachePath = $cachePath;
    }

    public function notify(GoalSetCreatedEvent $event)
    {
        $entity = $this->goalSetRepository->getById($event->getSetId());
        if (!$entity) {
            throw new \Exception("DOMAIN.GOAL_SET_NOT_FOUND");
        }

        $circuit = $entity->getCircuit();

        $collisionCircuits = $this->circuitRepository->getCollisions($circuit);
        $circuit->checkCollisions($collisionCircuits);

        $this->circuitRepository->add($circuit);
    }
}
