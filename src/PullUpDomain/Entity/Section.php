<?php

namespace PullUpDomain\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Section
{
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $description;

    /** @var User */
    protected $user;

    /** @var Goal[]|ArrayCollection */
    protected $goals;

    /** @var boolean */
    protected $removed;

    /** @var \DateTime */
    protected $createdAt;

    /** @var \DateTime */
    protected $updatedAt;

    /**
     * @param string $name
     * @param string $description
     * @param User $user
     * @param array $goals
     * @return Section
     */
    public static function create(string $name, string $description, User $user, array $goals = [])
    {
        $goals = array_map(function (Goal $goal) {
            return $goal;
        }, $goals);

        $entity = new self();
        $entity->name = $name;
        $entity->description = $description;
        $entity->user = $user;
        $entity->goals = new ArrayCollection();
        $entity->removed = false;

        foreach ($goals as $goal) {
            $entity->goals->add($goal);
        }

        return $entity;
    }

    public function getId()
    {
        return (string)$this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return ArrayCollection|Goal[]
     */
    public function getGoals()
    {
        return $this->goals;
    }

    /**
     * @return bool
     */
    public function isRemoved()
    {
        return $this->removed;
    }

    /**
     * @param Goal $goal
     */
    public function addGoal(Goal $goal)
    {
        $this->goals->add($goal);
    }

    /**
     * @param Goal $goal
     */
    public function removeGoal(Goal $goal)
    {
        $this->goals->removeElement($goal);
    }
}
