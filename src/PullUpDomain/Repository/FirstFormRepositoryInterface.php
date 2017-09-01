<?php

namespace PullUpDomain\Repository;

use PullUpDomain\Entity\FirstForm;
use PullUpDomain\Entity\User;

interface FirstFormRepositoryInterface
{
    /**
     * @param User $user
     * @return FirstForm
     */
    public function getOneByUser(User $user);

    public function add(FirstForm $entity);
    public function remove(FirstForm $entity);
}