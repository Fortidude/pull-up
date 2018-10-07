<?php

namespace PullUpDomain\Repository;

use PullUpDomain\Entity\GoalSet;
use PullUpDomain\Entity\User;

interface GoalSetRepositoryInterface
{
    /**
     * @param $id
     * @return GoalSet
     */
    public function getById($id);

    /**
     * @param User $user
     * @return GoalSet|null
     */
    public function getLastByUser(User $user);

    public function add(GoalSet $entity);
    public function remove(GoalSet $entity);

    public function flush();
}