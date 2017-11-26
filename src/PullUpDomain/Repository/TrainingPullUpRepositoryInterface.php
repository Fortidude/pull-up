<?php

namespace PullUpDomain\Repository;

use PullUpDomain\Entity\TrainingPullUp;
use PullUpDomain\Entity\User;

interface TrainingPullUpRepositoryInterface
{
    /**
     * @param User $user
     * @return TrainingPullUp[]
     */
    public function getListByUser(User $user);

    /**
     * @param User $user
     * @param $route
     * @return TrainingPullUp[]
     */
    public function getListByUserAndRoute(User $user, $route);

    /**
     * @return string
     */
    public function getStatisticallyHarderTrainingType();

    /**
     * @param User $user
     * @param int $route
     * @param string $type
     * @return boolean
     */
    public function isAlreadyDone(User $user, $route, $type);

    /**
     * @param User $user
     * @return integer
     */
    public function getLastFinishedRouteNumber(User $user);

    /**
     * @param User $user
     * @return TrainingPullUp
     */
    public function getLastDone(User $user);
    /**
     * @param User $user
     * @param int $route
     * @return TrainingPullUp
     */
    public function getFirstByRoute(User $user, $route);

    public function add(TrainingPullUp $entity);
    public function remove(TrainingPullUp $entity);

    public function flush();
}