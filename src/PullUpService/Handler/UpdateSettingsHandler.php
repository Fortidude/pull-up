<?php

namespace PullUpService\Handler;

use PullUpDomain\Entity\User;

use PullUpDomain\Repository\UserRepositoryInterface;

use PullUpService\Command\UpdateSettingsCommand;

class UpdateSettingsHandler
{
    /** @var UserRepositoryInterface */
    protected $userRepository;

    /** @var User */
    protected $user;

    private $eventBus;

    private $cachePath;

    public function __construct(
        UserRepositoryInterface $userRepository,
        User $user,
        $eventBus,
        $cachePath = null
    )
    {
        $this->userRepository = $userRepository;
        $this->user = $user;
        $this->eventBus = $eventBus;
        $this->cachePath = $cachePath;
    }

    public function handle(UpdateSettingsCommand $command)
    {
        if ($this->cachePath) {
            //file_put_contents($this->cachePath . '/first_form.json', json_encode($data));
        }

        if ($this->user->changeDaysAmountPerCircuit($command->circuitDuration)) {
            // event
        }
    }
}
