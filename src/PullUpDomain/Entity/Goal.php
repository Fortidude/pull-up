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

    protected $sets;

    protected $removed;

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

        $entity->removed = false;

        return $entity;
    }

    public function update(int $requiredSets = null, int $requiredReps = null, int $requiredWeight = null, int $requiredTime = null)
    {
        if (!$requiredSets && !$requiredReps && !$requiredWeight && !$requiredTime) {
            throw new \Exception("none type were selected");
        }

        $this->requiredSets = $requiredSets;
        $this->requiredReps = $requiredReps;
        $this->requiredWeight = $requiredWeight;
        $this->requiredTime = $requiredTime;
    }

    public function addSet(\DateTime $date, int $reps = null, int $weight = null, int $time = null)
    {
        $this->sets[] = GoalSet::create($this, $this->user, $date, $reps ?: 0, $weight ?: 0, $time ?: 0);
        return $this;
    }

    /**
     * @return string
     */
    public function getRequiredType()
    {
        if ($this->requiredSets) {
            return 'sets';
        } elseif ($this->requiredReps) {
            return 'reps';
        } elseif ($this->requiredWeight) {
            return 'weight';
        } elseif ($this->requiredTime) {
            return 'time';
        } else {
            return 'error';
        }
    }

    /**
     * @return int
     */
    public function getRequiredAmount()
    {
        if ($this->requiredSets) {
            return $this->requiredSets;
        } elseif ($this->requiredReps) {
            return $this->requiredReps;
        } elseif ($this->requiredWeight) {
            return $this->requiredWeight;
        } elseif ($this->requiredTime) {
            return $this->requiredTime;
        } else {
            return 0;
        }
    }

    public function getProgress()
    {

    }

    public function remove()
    {
        $this->removed = true;
    }

    public function restore()
    {
        $this->removed = false;
    }
}