<?php

namespace PullUpDomain\Repository;

use PullUpDomain\Entity\TrainingPullUpFirstForm as FirstForm;
use PullUpDomain\Entity\User;

interface TrainingPullUpFirstFormRepositoryInterface
{
    /**
     * @param User $user
     * @return FirstForm
     */
    public function getOneByUser(User $user);

    public function add(FirstForm $entity);
    public function remove(FirstForm $entity);

    public function flush();
}