<?php

namespace PullUpService\Handler;

use PullUpDomain\Repository\ExerciseRepositoryInterface;

use PullUpDomain\Repository\ExerciseVariantRepositoryInterface;
use PullUpDomain\Repository\GoalRepositoryInterface;
use PullUpService\Command\RemoveExerciseCommand;

class RemoveExerciseHandler
{
    /** @var ExerciseRepositoryInterface */
    protected $exerciseRepository;

    /** @var ExerciseVariantRepositoryInterface */
    protected $exerciseVariantRepository;

    /** @var GoalRepositoryInterface */
    protected $goalRepository;

    public function __construct(ExerciseRepositoryInterface $exerciseRepository,
                                ExerciseVariantRepositoryInterface $exerciseVariantRepository,
                                GoalRepositoryInterface $goalRepository)
    {
        $this->exerciseRepository = $exerciseRepository;
        $this->exerciseVariantRepository = $exerciseVariantRepository;
        $this->goalRepository = $goalRepository;
    }

    public function handle(RemoveExerciseCommand $command)
    {

        $exercise = $this->exerciseRepository->getByNameOrId($command->exerciseId);
        if (!$exercise) {
            throw new \Exception("Exercise not found", 404);
        }

        $goals = $this->goalRepository->getByExercise($exercise);
        if (count($goals) > 0) {
            foreach ($exercise->getExerciseVariants() as $variant) {
                $variant->remove();
            }
            $exercise->remove();
        } else {
            foreach ($exercise->getExerciseVariants() as $variant) {
                $this->exerciseVariantRepository->remove($variant);
            }

            $this->exerciseRepository->remove($exercise);
        }
    }
}
