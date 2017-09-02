<?php

namespace PullUpDomain\Repository;

use PullUpDomain\Entity\User;
use PullUpDomain\Entity\Goal;

interface GoalRepositoryInterface
{
    /**
     * @param User $user
     * @return Goal[]
     */
    public function getListByUser(User $user);

    public function add(Goal $entity);
    public function remove(Goal $entity);
}