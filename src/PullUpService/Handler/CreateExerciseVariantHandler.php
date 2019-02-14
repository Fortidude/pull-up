<?php

namespace PullUpService\Handler;

use PullUpDomain\Repository\ExerciseRepositoryInterface;

use PullUpService\Command\CreateExerciseVariantCommand;

class CreateExerciseVariantHandler
{
    /** @var ExerciseRepositoryInterface */
    protected $exerciseRepository;


    public function __construct(ExerciseRepositoryInterface $exerciseRepository)
    {
        $this->exerciseRepository = $exerciseRepository;
    }

    public function handle(CreateExerciseVariantCommand $command)
    {

        $exercise = $this->exerciseRepository->getByNameOrId($command->exerciseId);

        if (!$exercise) {
            throw new \Exception("Exercise not found", 404);

        }

        foreach ($exercise->getExerciseVariants() as $variant) {
            if (strtolower(($variant->getName())) == strtolower($command->name)) {
                throw new \Exception("Exercise already exist");
            }
        }


        $exercise->addExerciseVariant($command->name, '');
    }
}
