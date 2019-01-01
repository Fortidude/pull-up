<?php

namespace PullUpDomain\Entity;

class GoalSet
{
    protected $id;

    protected $goal;

    /** @var User */
    protected $user;

    /** @var Circuit */
    protected $circuit;

    protected $reps;
    protected $weight;
    protected $time;

    protected $difficultyLevel;

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
     * @param bool $difficultyLevel
     * @return GoalSet
     */
    public static function create(Goal $goal, User $user, \DateTime $date, int $reps, int $weight, int $time, int $difficultyLevel) : GoalSet
    {
        $entity = new self();
        $entity->goal = $goal;
        $entity->user = $user;

        $entity->date = $date;

        $entity->reps = $reps;
        $entity->weight = $weight;
        $entity->time = $time;

        $entity->difficultyLevel = $difficultyLevel;

        $entity->circuit = $user->getTrainingCircuitByDate($entity->date);

        return $entity;
    }

    public function changeCircuit(Circuit $circuit)
    {
        if (!$circuit->isForDate($this->date)) {
            throw new \Exception("DOMAIN.CIRCUIT_NOT_FOR_DATE");
        }

        $this->circuit = $circuit;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getValue()
    {
        if ($this->reps) {
            return $this->reps;
        }

        if ($this->time) {
            return $this->time;
        }

        return 1;
    }

    public function getWeight()
    {
        if ($this->weight) {
            return $this->weight;
        }

        return 0;
    }

    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed|Circuit
     */
    public function getCircuit()
    {
        if ($this->circuit) {
            return $this->circuit;
        }

        return $this->circuit = $this->user->getTrainingCircuitByDate($this->date);
    }
}