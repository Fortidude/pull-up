<?php

namespace PullUpService\Handler;

use PullUpDomain\Entity\TrainingPullUpFirstForm as FirstForm;
use PullUpDomain\Entity\User;
use PullUpDomain\Repository\TrainingPullUpFirstFormRepositoryInterface as FirstFormRepositoryInterface;
use PullUpService\Command\CreateTrainingPullUpFirstFormCommand as CreateFirstFormCommand;

class CreateTrainingPullUpFirstFormHandler
{
    /** @var FirstFormRepositoryInterface */
    protected $firstFormRepository;

    /** @var User */
    protected $user;

    private $cachePath;

    public function __construct(FirstFormRepositoryInterface $firstFormRepository, User $user, $cachePath = null)
    {
        $this->firstFormRepository = $firstFormRepository;
        $this->user = $user;
        $this->cachePath = $cachePath;
    }

    public function handle(CreateFirstFormCommand $command)
    {
        $data = json_decode($command->data, true);

        if ($this->cachePath) {
            //file_put_contents($this->cachePath . '/first_form.json', json_encode($data));
        }

        if ($this->user->isTrainingPullUpFirstFormFilled()) {
            throw new \Exception("PULL_UP_ALREADY_FILLED");
        }

        $this->user->fillTrainingPullUpFirstForm($data);
    }
}