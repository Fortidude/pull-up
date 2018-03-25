<?php

namespace PullUpService\Handler;

use PullUpDomain\Entity\Exercise;
use PullUpDomain\Entity\ExerciseVariant;
use PullUpDomain\Entity\Goal;
use PullUpDomain\Entity\Section;
use PullUpDomain\Entity\User;

use PullUpDomain\Repository\ExerciseRepositoryInterface;
use PullUpDomain\Repository\GoalRepositoryInterface;


use PullUpDomain\Repository\SectionRepositoryInterface;
use PullUpService\Command\CreateGoalCommand;


class CreateGoalHandler
{
    /** @var GoalRepositoryInterface */
    protected $goalRepository;

    /** @var ExerciseRepositoryInterface */
    protected $exerciseRepository;

    /** @var SectionRepositoryInterface */
    protected $sectionRepository;

    /** @var User */
    protected $user;

    private $cachePath;

    public function __construct(
        GoalRepositoryInterface $goalRepository,
        ExerciseRepositoryInterface $exerciseRepository,
        SectionRepositoryInterface $sectionRepository,
        User $user,
        $cachePath = null
    )
    {
        $this->goalRepository = $goalRepository;
        $this->exerciseRepository = $exerciseRepository;
        $this->sectionRepository = $sectionRepository;
        $this->user = $user;
        $this->cachePath = $cachePath;
    }

    public function handle(CreateGoalCommand $command)
    {
        if ($this->cachePath) {
            //file_put_contents($this->cachePath . '/first_form.json', json_encode($data));
        }

        $exerciseVariantSelected = count($command->exerciseVariant) > 0;

        $exercise = $this->exerciseRepository->getByNameOrId($command->exercise);
        $exerciseVariant = null;
        if (!$exercise) {
            $exercise = Exercise::create($command->exercise, '');

            if ($exerciseVariantSelected) {
                $exerciseVariant = ExerciseVariant::create($command->exerciseVariant, '', $exercise);
            }
        } elseif ($exerciseVariantSelected) {
            foreach ($exercise->getExerciseVariants() as $variant) {
                if ($variant->getName() == $command->exerciseVariant || $variant->getId() == $command->exerciseVariant) {
                    $exerciseVariant = $variant;
                    break;
                }
            }

            if (!$exerciseVariant) {
                $exerciseVariant = ExerciseVariant::create($command->exerciseVariant, '', $exercise);
            }
        }

        // @TODO sprawdzenie, czy użytkownik nie dodał wcześniej celu dla tego ćwiczenia i wariantu

        $goalName = $command->noSpecifiedGoal ? Goal::NO_GOAL_SPECIFIED_NAME : $command->name;
        $exist = $this->goalRepository->checkIfDuplicate($this->user, $exercise, $exerciseVariant);
        if ($exist instanceof Goal) {
            $exist->restore();
            return;
        }

        $entity = Goal::create($goalName,
            $command->description,
            $this->user,
            $exercise,
            $exerciseVariant,
            $command->sets,
            $command->reps,
            $command->weight,
            $command->time
        );


        if ($command->section) {
            $section = $this->sectionRepository->getByUserAndName($this->user, $command->section);
            if (!$section) {
                $section = Section::create($command->section, '', $this->user);
                $this->sectionRepository->add($section);
            }
            $entity->moveToSection($section);

        }

        $this->goalRepository->add($entity);
    }
}