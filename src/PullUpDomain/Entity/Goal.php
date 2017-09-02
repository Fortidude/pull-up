<?php

namespace PullUpDomain\Entity;

class Goal
{
    protected $id;

    protected $user;
    protected $exercise;
    protected $exerciseVariant;

    protected $name;
    protected $description;

    protected $requiredSets;
    protected $requiredWeight;
    protected $requiredTime;
    protected $requiredReps;

    protected $createdAt;
    protected $updatedAt;

    public function getId()
    {
        return (string)$this->id;
    }

    /**
     * @param string $name
     * @param string $description
     * @param User $user
     * @param Exercise $exercise
     * @param ExerciseVariant $exerciseVariant
     * @param int|null $requiredSets
     * @param int|null $requiredReps
     * @param int|null $requiredWeight
     * @param int|null $requiredTime
     * @return Goal
     * @throws \Exception
     */
    public static function create(
        string $name,
        string $description,
        User $user,
        Exercise $exercise,
        ExerciseVariant $exerciseVariant,
        int $requiredSets = null,
        int $requiredReps = null,
        int $requiredWeight = null,
        int $requiredTime = null
    ) : Goal
    {
        if (!$requiredSets && !$requiredReps && !$requiredWeight && !$requiredTime) {
            throw new \Exception("none type were selected");
        }

        $entity = new self();
        $entity->name = $name;
        $entity->description = $description;

        $entity->user = $user;

        $entity->exercise = $exercise;
        $entity->exerciseVariant = $exerciseVariant;

        $entity->requiredSets = $requiredSets;
        $entity->requiredReps = $requiredReps;
        $entity->requiredWeight = $requiredWeight;
        $entity->requiredTime = $requiredTime;

        return $entity;
    }

    public function addSet()
    {

    }

    public function getProgress()
    {

    }
}