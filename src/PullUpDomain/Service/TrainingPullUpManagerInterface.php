<?php

namespace PullUpDomain\Service;

use PullUpDomain\Entity\TrainingPullUp;
use PullUpDomain\Entity\User;

interface TrainingPullUpManagerInterface
{
    /**
     * @param User $user
     * @return TrainingPullUp
     */
    public function getCurrent(User $user);

    /**
     * @param User $user
     * @param $route
     * @return TrainingPullUp
     */
    public function getHardestByRoute(User $user, $route);
}