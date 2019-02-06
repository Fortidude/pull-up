<?php

namespace PullUpService\Handler;

use PullUpDomain\Entity\Exercise;
use PullUpDomain\Entity\ExerciseVariant;
use PullUpDomain\Entity\Goal;
use PullUpDomain\Entity\User;

use PullUpDomain\Repository\ExerciseRepositoryInterface;

use PullUpService\Command\UpdateExerciseCommand;

class UpdateExerciseHandler
{
    /** @var ExerciseRepositoryInterface */
    protected $exerciseRepository;

    /** @var User */
    protected $user;

    private $cachePath;

    public function __construct(
        ExerciseRepositoryInterface $exerciseRepository,
        User $user
    )
    {
        $this->exerciseRepository = $exerciseRepository;
        $this->user = $user;
    }

    public function handle(UpdateExerciseCommand $command)
    {
        $exercise = $this->exerciseRepository->getByNameOrId($command->id);

        if (!$exercise) {
            throw new \Exception("Unable to find Exercise \"{$command->id}\"", 404);
        }

        $exercise->update($command->name, $command->description, $command->isCardio);
    }
}