<?php

namespace PullUpService\Handler;

use PullUpDomain\Entity\User;
use PullUpDomain\Service\AuthenticationManagerInterface;
use PullUpDomain\Service\ProfileManagerInterface;
use PullUpService\Command\LoginByFacebookCommand;

class RegisterHandler
{
    /** @var ProfileManagerInterface */
    protected $userManager;

    /** @var AuthenticationManagerInterface */
    protected $authenticationManager;

    public function __construct(ProfileManagerInterface $userManager, AuthenticationManagerInterface $authenticationManager)
    {
        $this->userManager = $userManager;
        $this->authenticationManager = $authenticationManager;
    }

    public function handle(LoginByFacebookCommand $command)
    {
       
    }
}