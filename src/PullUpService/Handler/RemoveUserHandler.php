<?php

namespace PullUpService\Handler;

use PullUpDomain\Entity\User;

use PullUpDomain\Repository\ExerciseRepositoryInterface;
use PullUpDomain\Repository\ExerciseVariantRepositoryInterface;

use PullUpDomain\Repository\GoalRepositoryInterface;
use PullUpDomain\Repository\GoalSetRepositoryInterface;

use PullUpDomain\Repository\CircuitRepositoryInterface;
use PullUpDomain\Repository\SectionRepositoryInterface;

use PullUpService\Command\RemoveUserCommand;

class RemoveUserHandler
{
    /** @var User */
    protected $user;

    /** @var ExerciseRepositoryInterface */
    protected $exerciseRepository;

    /** @var ExerciseVariantRepositoryInterface */
    protected $exerciseVariantRepository;

    /** @var GoalRepositoryInterface */
    protected $goalRepository;

    /** @var GoalSetRepositoryInterface */
    protected $goalSetRepository;

    /** @var CircuitRepositoryInterface */
    protected $circuitRepository;

    /** @var SectionRepositoryInterface */
    protected $sectionRepository;

    public function __construct(User $user
    )
    {
        $this->user = $user;
    }

    public function handle(RemoveUserCommand $command)
    {
        $newName = "removed@{$this->user->getId()}";
    
        $this->user->setEnabled(false);
        $this->user->setEmailCanonical($newName);
        $this->user->setEmail($newName);
        $this->user->setUsernameCanonical($newName);
        $this->user->setUsername($newName);
        $this->user->updateUser(substr($newName, 0, 24), null);
        $this->user->removeDeviceId();
        $this->user->removeAvatar();
        $this->user->removeFacebookId();
    }
}
