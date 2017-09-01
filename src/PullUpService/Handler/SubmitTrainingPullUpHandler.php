<?php

namespace PullUpService\Handler;

use PullUpDomain\Entity\User;
use PullUpDomain\Repository\TrainingPullUpRepositoryInterface;

use PullUpService\Command\SubmitTrainingPullUpCommand;

class SubmitTrainingPullUpHandler
{
    /** @var TrainingPullUpRepositoryInterface */
    protected $calendarRepository;

    /** @var User */
    protected $user;

    private $cachePath;

    public function __construct(TrainingPullUpRepositoryInterface $calendarRepository, User $user, $cachePath = null)
    {
        $this->calendarRepository = $calendarRepository;
        $this->user = $user;
        $this->cachePath = $cachePath;
    }

    public function handle(SubmitTrainingPullUpCommand $command)
    {
        $data = json_decode($command->data, true);

        if ($this->cachePath) {
            file_put_contents($this->cachePath . '/training.json', json_encode($data));
        }

        if (!$this->user->isFirstFormFilled()) {
            throw new \Exception();
        }

        $this->validate($data);

        $maxRepsThisRoute = 10;
        $route = 1;

        $alreadyDone = $this->calendarRepository->isAlreadyDone($this->user, $route, $data['type']);

        if (!$alreadyDone) {
            $this->user->addTrainingPullUp($route, $data['type'], $data['level'], $maxRepsThisRoute, serialize($data));
        }
    }

    private function validate(array $data)
    {
        if (!array_key_exists('type', $data) || !$data['type']) {
            throw new \Exception("Type of training is missing");
        }
    }
}