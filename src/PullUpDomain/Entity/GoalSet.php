<?php

namespace PullUpDomain\Entity;

class GoalSet
{
    protected $id;

    protected $goal;
    protected $user;

    protected $reps;
    protected $weight;
    protected $time;

    protected $date;

    protected $createdAt;
    protected $updatedAt;

    public function getId()
    {
        return (string)$this->id;
    }

    /**
     * @param Goal $goal
     * @param User $user
     * @param \DateTime $date
     * @param int $reps
     * @param int $weight
     * @param int $time
     * @return GoalSet
     */
    public static function create(Goal $goal, User $user, \DateTime $date, int $reps, int $weight, int $time) : GoalSet
    {
        $entity = new self();
        $entity->goal = $goal;
        $entity->user = $user;

        $entity->date = $date;

        $entity->reps = $reps;
        $entity->weight = $weight;
        $entity->time = $time;

        return $entity;
    }

    public function getValue()
    {
        if ($this->reps) {
            return $this->reps;
        }

        if ($this->weight) {
            return $this->weight;
        }

        if ($this->time) {
            return $this->time;
        }

        return null;
    }
}