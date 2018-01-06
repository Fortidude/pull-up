<?php

namespace PullUpService\Handler;

use PullUpDomain\Entity\User;

use PullUpDomain\Repository\UserRepositoryInterface;

use PullUpService\Command\UpdateSettingsCommand;
use PullUpService\Event\UserDurationPerCircuitChangedEvent;

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
          //  file_put_contents($this->cachePath . '/first_form.json', $command->name);
        }

        if ($this->userRepository->checkIfExist([$command->name], [$this->user->getId()])) {
            throw new \Exception("USER_ALREADY_EXIST");
        }

        if ($this->user->changeDaysAmountPerCircuit($command->daysPerCircuit)) {
            $event = new UserDurationPerCircuitChangedEvent($this->user->getId());
            $this->eventBus->handle($event);
        }

        $this->user->updateUser($command->name);

        if ($command->plannerCustomMode) {
            $this->user->switchToCustomPlannerMode();
        } else {
            $this->user->switchToDailyPlannerMode();
        }
    }
}
