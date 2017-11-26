<?php

namespace PullUpService\Subscriber;

use PullUpDomain\Entity\User;
use PullUpDomain\Entity\Circuit;
use PullUpDomain\Repository\CircuitRepositoryInterface;
use PullUpDomain\Repository\GoalSetRepositoryInterface;

use PullUpDomain\Repository\UserRepositoryInterface;
use PullUpService\Event\GoalSetPreCreateEvent;

class GoalSetPreCreateSubscriber
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

    public function notify(GoalSetPreCreateEvent $event)
    {
        $user = $this->userRepository->getOne($event->getUserId());
        $date = $event->getDate();

        /** @var Circuit $circuit */
        $circuit = $this->circuitRepository->getByUserAndDate($user, $date);
        if ($circuit) {
            return;
        }

        /** @var Circuit $lastDone */
        $lastDone = $this->circuitRepository->getLastCircuit($user);
        $interval = new \DateInterval("P{$user->getDaysPerCircuit()}D");
        $period = new \DatePeriod($lastDone->getEndAt(), $interval, $date);

        if ($lastDone && $lastDone->getEndAt() < $date) {
            $lastDone->finish();
        }

        $created = [];
        foreach ($period as $key => $startDate) {
            $circuitBetween = $user->getTrainingCircuitByDate($startDate->add(new \DateInterval("P1D")));

            if ($circuitBetween->getEndAt() < $date) {
                $circuitBetween->finish();
            }

            $created[$key] = $circuitBetween;
            if ($key > 0) {
                $lastEndAt = $created[$key - 1]->getEndAt();
                $lastEndAt->setTime(0, 0, 0);

                if ($lastEndAt->format('Y-m-d') === $circuitBetween->getStartAt()->format('Y-m-d')) {
                    $circuitBetween->getStartAt()->add(new \DateInterval("P1D"));
                }
            }
        }

        $this->circuitRepository->flush();
    }
}
