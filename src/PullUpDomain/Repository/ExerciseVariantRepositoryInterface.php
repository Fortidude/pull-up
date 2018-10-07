<?php

namespace PullUpDomain\Repository;

use PullUpDomain\Entity\Exercise;
use PullUpDomain\Entity\ExerciseVariant;

interface ExerciseVariantRepositoryInterface
{
    /**
     * @return ExerciseVariant[]
     */
    public function getList();

    /**
     * @param Exercise $exercise
     * @return ExerciseVariant[]
     */
    public function getListByExercise(Exercise $exercise);

    public function add(ExerciseVariant $entity);
    public function remove(ExerciseVariant $entity);

    public function flush();
}