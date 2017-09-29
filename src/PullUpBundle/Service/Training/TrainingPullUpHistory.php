<?php

namespace PullUpBundle\Service\Training;

use PullUpDomain\Entity\User;
use PullUpDomain\Repository\TrainingPullUpRepositoryInterface;

use PullUpDomain\Data\TrainingPullUp;

class TrainingPullUpHistory
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
     * @param User $user
     * @return array
     */
    public function getHistory(User $user)
    {
        return $this->trainingPullUpRepository->getListByUser($user);
    }
}