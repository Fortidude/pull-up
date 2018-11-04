<?php

namespace PullUpService\Handler;

use PullUpDomain\Service\AuthenticationManagerInterface;
use PullUpDomain\Service\ProfileManagerInterface;
use PullUpService\Command\PasswordChangeCommand;

class PasswordChangeHandler
{
    /** @var ProfileManagerInterface */
    protected $userManager;

    public function __construct(ProfileManagerInterface $userManager) {
        $this->userManager = $userManager;
    }

    public function handle(PasswordChangeCommand $command)
    {
        $user = $this->userManager->findUserByConfirmationToken($command->key);
        if (!$user || $command->email !== $user->getEmail()) {
            throw new \Exception("USER_NOT_FOUND");
        }

        $user->setConfirmationToken(null);
        $user->setPlainPassword($command->password);

        $this->userManager->update($user);
    }
}
