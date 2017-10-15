<?php

namespace PullUpService\Handler;

use PullUpDomain\Entity\User;
use PullUpDomain\Repository\GoalRepositoryInterface;

use PullUpService\Command\DisableGoalCommand;

class DisableGoalHandler
{
    /** @var GoalRepositoryInterface */
    protected $goalRepository;

    /** @var User */
    protected $user;

    private $cachePath;

    public function __construct(
        GoalRepositoryInterface $goalRepository,
        User $user,
        $cachePath = null
    )
    {
        $this->goalRepository = $goalRepository;
        $this->user = $user;
        $this->cachePath = $cachePath;
    }

    public function handle(DisableGoalCommand $command)
    {
        if ($this->cachePath) {
            //file_put_contents($this->cachePath . '/first_form.json', json_encode($data));
        }

        $entity = $this->goalRepository->getByUserAndId($this->user, $command->id);
        $entity->remove();
    }
}