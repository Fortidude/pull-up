<?php

namespace PullUpService\Handler;

use PullUpDomain\Entity\User;
use PullUpDomain\Service\AuthenticationManagerInterface;
use PullUpDomain\Service\ProfileManagerInterface;
use PullUpService\Command\RegisterCommand;

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

    public function handle(RegisterCommand $command)
    {
        $exist = $this->userManager->checkIfExist([$command->email, $command->username]);
        if ($exist) {
            throw new \Exception("USER_ALREADY_EXIST");
        }

        $user = User::createByClassicRegister($command->email, $command->username, $command->password);
        $user->setLastLogin(new \DateTime("now"));
        $this->userManager->update($user);
    }
}
