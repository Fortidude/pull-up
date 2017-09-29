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
            throw new \Exception('DOMAIN.FIRST_FORM_NOT_FILLED');
        }

        $this->validate($data);

        $route = $this->calendarRepository->getLastFinishedRouteNumber($this->user) + 1;
        if ($data['type'] === 'one' && isset($data['set_1'])) {
            $maxRepsThisRoute = $data['set_1'];
        } else {
            $firstDone = $this->calendarRepository->getFirstByRoute($this->user, $route);
            if (!$firstDone) {
                throw new \Exception("DOMAIN.THIS_ROUTE_FIRST_TRAINING_MISSING");
            }

            $maxRepsThisRoute = $firstDone->getReps();
        }

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