<?php

namespace PullUpService\Subscriber;

use PullUpDomain\Entity\GoalSet;
use PullUpDomain\Repository\CircuitRepositoryInterface;
use PullUpDomain\Repository\GoalSetRepositoryInterface;
use PullUpDomain\Repository\UserRepositoryInterface;

use PullUpService\Event\UserDurationPerCircuitChangedEvent;

class UserDurationPerCircuitChangedSubscriber
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

    public function notify(UserDurationPerCircuitChangedEvent $event)
    {
        $user = $this->userRepository->getOne($event->getUserId());
        $circuitDuration = $user->getDaysPerCircuit();
        $currentCircuit = $user->getCurrentTrainingCircuit();

        $circuits = $this->circuitRepository->getAllFuture($user, [$currentCircuit->getId()]);

        /** @var GoalSet[] $sets */
        $sets = [];

        foreach ($currentCircuit->getSets() as $set) {
            if (!$currentCircuit->isForDate($set->getDate())) {
                $sets[] = $set;
            }
        }

        $endAt = clone $currentCircuit->getEndAt();
        foreach ($circuits as $circuit) {
            $sets = array_merge($sets, $circuit->getSets()->toArray());

            $endAt = $endAt->add(new \DateInterval("P1D"));
            $circuit->setStartAt(clone $endAt, $circuitDuration);
            $endAt = clone $circuit->getEndAt();

        }

        $this->circuitRepository->flush();

        foreach ($sets as $set) {
            $circuitByDate = $this->circuitRepository->getByUserAndDate($user, $set->getDate());
            $set->changeCircuit($circuitByDate);
        }

        $this->circuitRepository->flush();
    }
}
