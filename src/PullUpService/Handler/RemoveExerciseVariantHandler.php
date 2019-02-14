<?php

namespace PullUpService\Handler;

use PullUpDomain\Repository\ExerciseRepositoryInterface;

use PullUpDomain\Repository\ExerciseVariantRepositoryInterface;
use PullUpDomain\Repository\GoalRepositoryInterface;
use PullUpService\Command\RemoveExerciseVariantCommand;

class RemoveExerciseVariantHandler
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

    public function handle(RemoveExerciseVariantCommand $command)
    {

        $exercise = $this->exerciseRepository->getByNameOrId($command->exerciseId);

        if (!$exercise) {
            throw new \Exception("Exercise not found", 404);

        }

        foreach ($exercise->getExerciseVariants() as $variant) {
            if ($variant->getId() === $command->variantId) {

                $goals = $this->goalRepository->getByExerciseVariant($variant);
                if (count($goals) > 0) {
                    $variant->remove();
                } else {
                    $exercise->removeExerciseVariant($variant);
                    $this->exerciseVariantRepository->remove($variant);
                }
            }
        }
    }
}
