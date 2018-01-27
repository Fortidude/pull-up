<?php

namespace PullUpDomain\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Goal
{
    CONST NO_GOAL_SPECIFIED_NAME = "no goal specified name";

    protected $id;

    /** @var User */
    protected $user;

    /** @var Exercise */
    protected $exercise;

    /** @var ExerciseVariant | null */
    protected $exerciseVariant;

    protected $name;
    protected $description;

    protected $requiredSets;
    protected $requiredWeight;
    protected $requiredTime;
    protected $requiredReps;

    /** @var GoalSet[] */
    protected $sets;

    /** @var Section */
    protected $section;

    protected $noSpecifiedGoal;
    protected $removed;

    protected $lastSetAdded;
    protected $createdAt;
    protected $updatedAt;

    public function __construct()
    {
        $this->sets = new ArrayCollection();
    }

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
        ExerciseVariant $exerciseVariant = null,
        int $requiredSets = null,
        int $requiredReps = null,
        int $requiredWeight = null,
        int $requiredTime = null
    ) : Goal
    {
        if (!$requiredSets && !$requiredReps && !$requiredWeight && !$requiredTime && $name !== self::NO_GOAL_SPECIFIED_NAME) {
            throw new \Exception("none type were selected");
        }

        $entity = new self();

        $entity->noSpecifiedGoal = false;
        if ($name === self::NO_GOAL_SPECIFIED_NAME) {
            $entity->noSpecifiedGoal = true;
        }

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

        $entity->lastSetAdded = new \DateTime("now");

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
        $set = GoalSet::create($this, $this->user, $date, $reps ?: 0, $weight ?: 0, $time ?: 0);
        $this->sets[] = $set;

        $this->lastSetAdded = new \DateTime("now");
        return $set;
    }

    /**
     * @return bool
     */
    public function isRequiredSetType()
    {
        return (bool)$this->requiredSets;
    }

    /**
     * @return string
     */
    public function getRequiredType()
    {
        if ($this->noSpecifiedGoal) {
            return 'none';
        }

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
     * @return Exercise
     */
    public function getExercise()
    {
        return $this->exercise;
    }

    /**
     * @return string
     */
    public function getExerciseName()
    {
        return $this->exercise->getName();
    }

    /**
     * @return string
     */
    public function getExerciseVariantName()
    {
        return $this->exerciseVariant ? $this->exerciseVariant->getName() : '';
    }

    /**
     * @return string
     */
    public function getSectionName()
    {
        return $this->section ? $this->section->getName() : 'other';
    }

    /**
     * @return ArrayCollection|GoalSet[]
     */
    public function getSets()
    {
        return $this->sets;
    }

    /**
     * @return int
     */
    public function getRequiredAmount()
    {
        if ($this->noSpecifiedGoal) {
            return 0;
        }

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

    /**
     * @param \DateTime $first
     * @param \DateTime|null $second
     * @return bool
     */
    public function wasUpdatedBetween(\DateTime $first, \DateTime $second = null)
    {
        if ($this->lastSetAdded && $this->lastSetAdded <= $first && (!$second || $this->lastSetAdded > $second)) {
            return true;
        }

        return false;
    }

    public function getLastSetValue()
    {
        if ($this->noSpecifiedGoal) {
            return null;
        }

        if (isset($this->sets[0])) {
            return $this->sets[0]->getValue();
        }

        return null;
    }

    public function doneThisCircuit()
    {
        $done = 0;

        /** @var Circuit $currentCircuit */
        $currentCircuit = $this->user->getCurrentTrainingCircuit();
        foreach ($this->sets as $set) {
            if ($set->getCircuit()->getId() === $currentCircuit->getId()) {
                if ($this->isRequiredSetType()) {
                    $done++;
                    continue;
                }

                $done += $set->getValue();
            }
        }

        return $done;
    }

    public function leftThisCircuit()
    {
        if ($this->noSpecifiedGoal) {
            return 0;
        }

        $total = $this->getRequiredAmount();
        $done = $this->doneThisCircuit();

        return $total - $done;
    }

    /**
     * @param Section $section
     */
    public function moveToSection(Section $section)
    {
        if ($this->section && $this->section->getId() === $section->getId()) {
            return;
        }
        
        $this->section = $section;
        $section->addGoal($this);
    }
}
