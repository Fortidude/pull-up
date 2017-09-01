<?php

namespace PullUpBundle\Service\Profile;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

use PullUpDomain\Entity\User;
use PullUpDomain\Service\AuthenticationManagerInterface;

class AuthenticationManager implements AuthenticationManagerInterface
{
    /** @var JWTTokenManagerInterface */
    protected $tokenManager;

    public function __construct(JWTTokenManagerInterface $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    public function create(User $user)
    {
        $this->tokenManager->create($user);
    }
}