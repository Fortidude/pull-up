<?php

namespace PullUpDomain\Service;

use PullUpDomain\Entity\User;

interface ProfileManagerInterface
{
    public function findUserBy(array $criteria);

    public function checkIfExist(array $strings): bool;

    public function findUsers();

    public function update(User $user);
}