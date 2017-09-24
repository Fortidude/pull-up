<?php

namespace PullUpBundle\Service\Training;

use PullUpDomain\Entity\User;
use PullUpDomain\Repository\TrainingPullUpRepositoryInterface;
use PullUpDomain\Service\TrainingPullUpManagerInterface;

use PullUpDomain\Data\TrainingPullUp;

class TrainingPullUpManager implements TrainingPullUpManagerInterface
{
    /** @var TrainingPullUpRepositoryInterface */
    protected $trainingPullUpRepository;

    /** @var \DateInterval|null */
    protected $interval = null;

    public function __construct(TrainingPullUpRepositoryInterface $trainingPullUpRepository)
    {
        $this->trainingPullUpRepository = $trainingPullUpRepository;
    }

    /**
     * Each training has his own \DateInterval. Overwrite it for all.
     * To disable interval use false.
     *
     * @param \DateInterval|boolean $interval
     * @return $this
     */
    public function setIntervalBetweenTraining($interval)
    {
        $this->interval = $interval;
        return $this;
    }

    public function getCurrent(User $user)
    {
        $trainingService = new TrainingPullUp();

        $lastDone = $this->trainingPullUpRepository->getLastDone($user);
        if (!$lastDone) {
            return $trainingService->getFirst();
        }

        $lastDoneTraining = $trainingService->getByType($lastDone->getType());
        $training = $trainingService->getNext($lastDone->getType(), $lastDone->getReps());
        if ($training['type'] == 'five') {
            $hardestType = null;
            $hardest = $this->getHardestByRoute($user, $lastDone->getRoute());
            if ($hardest) {
                $hardestType = $hardest->getType();
            } else {
                $hardestType = $this->trainingPullUpRepository->getStatisticallyHarderTrainingType();
            }

            $hardestTraining = $trainingService->getByType($hardestType, $lastDone->getReps());
            $hardestTraining['type'] = 'five';
            $hardestTraining['interval'] = $training['interval'];
            $training = $hardestTraining;
        }

        if ($this->interval !== false) {
            $interval = ($this->interval instanceof \DateInterval) ? $this->interval : $lastDoneTraining['interval'];
            if (!$lastDone->isNextAvailable($interval)) {
                return $trainingService->getNextIsNotAvailableYetCauseOfInterval($lastDone->getCreatedAt(), $interval);
            }
        }

        return $training;
    }

    public function getHardestByRoute(User $user, $route)
    {
        $results = $this->trainingPullUpRepository->getListByUserAndRoute($user, $route);

        $hardest = null;
        $hardestLevelAmount = 0;
        foreach ($results as $trainingPullUp) {
            if (!$hardest) {
                $hardestLevelAmount = 1;
                $hardest = $trainingPullUp;
                continue;
            }

            if ($trainingPullUp->getLevel() > $hardest->getLevel()) {
                $hardest = $trainingPullUp;
                $hardestLevelAmount = 1;
                continue;
            }

            if ($trainingPullUp->getLevel() == $hardest->getLevel()) {
                $hardestLevelAmount++;
            }
        }

        if (!$hardest || $hardestLevelAmount > 1) {
            return null;
        }

        return $hardest;
    }
}