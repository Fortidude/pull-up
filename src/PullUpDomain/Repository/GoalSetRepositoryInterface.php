<?php

namespace PullUpDomain\Repository;

use PullUpDomain\Entity\GoalSet;

interface GoalSetRepositoryInterface
{
    public function add(GoalSet $entity);
    public function remove(GoalSet $entity);
}