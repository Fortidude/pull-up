<?php

namespace PullUpService\Subscriber;

use PullUpDomain\Entity\User;
use PullUpDomain\Entity\Circuit;
use PullUpDomain\Repository\CircuitRepositoryInterface;
use PullUpDomain\Repository\GoalSetRepositoryInterface;

use PullUpService\Event\GoalSetCreatedEvent;

class GoalSetCreatedSubscriber
{
    protected $goalSetRepository;
    protected $circuitRepository;

    public function __construct(
        GoalSetRepositoryInterface $goalSetRepository,
        CircuitRepositoryInterface $circuitRepository
    )
    {
        $this->goalSetRepository = $goalSetRepository;
        $this->circuitRepository = $circuitRepository;
    }

    public function notify(GoalSetCreatedEvent $event)
    {
        $entity = $this->goalSetRepository->getById($event->getSetId());
        if (!$entity) {
            throw new \Exception("DOMAIN.GOAL_SET_NOT_FOUND");
        }

        $circuit = $entity->getCircuit();
        $circuit->addSet($entity);

        $collisionCircuits = $this->circuitRepository->getCollisions($circuit);
        $circuit->checkCollisions($collisionCircuits);

        $this->circuitRepository->add($circuit);
    }
}
