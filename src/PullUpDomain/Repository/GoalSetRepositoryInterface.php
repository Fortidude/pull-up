<?php

namespace PullUpDomain\Repository;

use PullUpDomain\Entity\GoalSet;

interface GoalSetRepositoryInterface
{
    /**
     * @param $id
     * @return GoalSet
     */
    public function getById($id);

    public function add(GoalSet $entity);
    public function remove(GoalSet $entity);

    public function flush();
}