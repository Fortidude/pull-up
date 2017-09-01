<?php

namespace PullUpDomain\Service;

use PullUpDomain\Entity\User;

interface AuthenticationManagerInterface
{
    public function create(User $user);
}