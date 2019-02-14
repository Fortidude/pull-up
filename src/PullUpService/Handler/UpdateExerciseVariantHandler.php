<?php

namespace PullUpService\Handler;

use PullUpDomain\Repository\ExerciseRepositoryInterface;

use PullUpService\Command\UpdateExerciseVariantCommand;

class UpdateExerciseVariantHandler
{
    /** @var ExerciseRepositoryInterface */
    protected $exerciseRepository;


    public function __construct(ExerciseRepositoryInterface $exerciseRepository)
    {
        $this->exerciseRepository = $exerciseRepository;
    }

    public function handle(UpdateExerciseVariantCommand $command)
    {

        $exercise = $this->exerciseRepository->getByNameOrId($command->exerciseId);
        if (!$exercise) {
            throw new \Exception("Exercise not found", 404);

        }

        foreach ($exercise->getExerciseVariants() as $variant) {
            if ($variant->getId() === $command->variantId) {
                $variant->update($command->name);
            }
        }
    }
}
