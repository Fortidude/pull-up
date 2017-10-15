<?php

namespace PullUpService\Handler;

use PullUpDomain\Entity\Exercise;
use PullUpDomain\Entity\ExerciseVariant;
use PullUpDomain\Entity\Goal;
use PullUpDomain\Entity\User;

use PullUpDomain\Repository\ExerciseRepositoryInterface;
use PullUpDomain\Repository\GoalRepositoryInterface;


use PullUpService\Command\CreateGoalCommand;


class CreateGoalHandler
{
    /** @var GoalRepositoryInterface */
    protected $goalRepository;

    /** @var ExerciseRepositoryInterface */
    protected $exerciseRepository;

    /** @var User */
    protected $user;

    private $cachePath;

    public function __construct(
        GoalRepositoryInterface $goalRepository,
        ExerciseRepositoryInterface $exerciseRepository,
        User $user,
        $cachePath = null
    )
    {
        $this->goalRepository = $goalRepository;
        $this->exerciseRepository = $exerciseRepository;
        $this->user = $user;
        $this->cachePath = $cachePath;
    }

    public function handle(CreateGoalCommand $command)
    {
        if ($this->cachePath) {
            //file_put_contents($this->cachePath . '/first_form.json', json_encode($data));
        }

        $exercise = $this->exerciseRepository->getByNameOrId($command->exercise);
        $exerciseVariant = null;
        if (!$exercise) {
            $exercise = Exercise::create($command->exercise, '');
            $exerciseVariant = ExerciseVariant::create($command->exerciseVariant, '', $exercise);

            //$this->exerciseRepository->add($exercise);
        } else {
            foreach ($exercise->getExerciseVariants() as $variant) {
                if ($variant->getName() == $command->exerciseVariant || $variant->getId() == $command->exerciseVariant) {
                    $exerciseVariant = $variant;
                    break;
                }
            }
        }

        // @TODO sprawdzenie, czy użytkownik nie dodał wcześniej celu dla tego ćwiczenia i wariantu

        $entity = Goal::create($command->name,
            $command->description,
            $this->user,
            $exercise,
            $exerciseVariant,
            $command->sets,
            $command->reps,
            $command->weight,
            $command->time
        );

        $this->goalRepository->add($entity);
    }
}