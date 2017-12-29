<?php

namespace PullUpService\Handler;

use PullUpDomain\Entity\Exercise;
use PullUpDomain\Entity\ExerciseVariant;
use PullUpDomain\Entity\Goal;
use PullUpDomain\Entity\User;

use PullUpDomain\Repository\ExerciseRepositoryInterface;

use PullUpService\Command\CreateExerciseCommand;

class CreateExerciseHandler
{
    /** @var ExerciseRepositoryInterface */
    protected $exerciseRepository;

    /** @var User */
    protected $user;

    private $cachePath;

    public function __construct(
        ExerciseRepositoryInterface $exerciseRepository,
        User $user,
        $cachePath = null
    )
    {
        $this->exerciseRepository = $exerciseRepository;
        $this->user = $user;
        $this->cachePath = $cachePath;
    }

    public function handle(CreateExerciseCommand $command)
    {
        if ($this->cachePath) {
            //file_put_contents($this->cachePath . '/first_form.json', json_encode($data));
        }

        $exerciseVariantSelected = strlen($command->variantName) > 0;
        $exercise = $this->exerciseRepository->getByNameOrId($command->name);

        $exerciseVariant = null;
        if (!$exercise) {
            $exercise = Exercise::create($command->name, '');

            if ($exerciseVariantSelected) {
                $exercise->addExerciseVariant($command->variantName, '');
            }
        } elseif ($exerciseVariantSelected) {
            foreach ($exercise->getExerciseVariants() as $variant) {
                if ($variant->getName() == $command->variantName || $variant->getId() == $command->variantName) {
                    $exerciseVariant = $variant;
                    break;
                }
            }

            if (!$exerciseVariant) {
                $exercise->addExerciseVariant($command->variantName, '');
            }
        }


        $this->exerciseRepository->add($exercise);
    }
}