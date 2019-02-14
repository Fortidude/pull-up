<?php

namespace PullUpDomain\Repository;

use PullUpDomain\Entity\Exercise;
use PullUpDomain\Entity\ExerciseVariant;
use PullUpDomain\Entity\User;
use PullUpDomain\Entity\Goal;
use PullUpDomain\Repository\Response\GoalStatisticsResponse;

interface GoalRepositoryInterface
{
    /**
     * @param $id
     * @return Goal|null
     */
    public function getById($id);

    /**
     * @param User $user
     * @param $id
     * @return Goal
     */
    public function getByUserAndId(User $user, $id);

    /**
     * @param User $user
     * @return Goal[]
     */
    public function getListByUser(User $user);

    /**
     * @param User $user
     * @param Exercise $exercise
     * @param ExerciseVariant|null $variant
     * @return Goal | null
     */
    public function checkIfDuplicate(User $user, Exercise $exercise, ExerciseVariant $variant = null);

    /**
     * @param User $user
     * @return array
     */
    public function getPlannerByUser(User $user) : array;
    /**
     * @param User $user
     * @return array
     */
    public function getCalendarPlannerByUser(User $user) : array;

    /**
     * @param User $user
     * @return GoalStatisticsResponse
     */
    public function getStatistics(User $user) : GoalStatisticsResponse;

    /**
     * @param ExerciseVariant $exerciseVariant
     * @return Goal[]
     */
    public function getByExerciseVariant(ExerciseVariant $exerciseVariant) : array;

    /**
     * @param Exercise $exercise
     * @return Goal[]
     */
    public function getByExercise(Exercise $exercise) : array;

    public function add(Goal $entity);
    public function remove(Goal $entity);

    public function flush();
}