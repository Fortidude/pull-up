<?php

namespace PullUpDomain\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Exercise
{
    protected $id;

    protected $name;
    protected $description;
    protected $isCardio;

    protected $exerciseVariants;

    protected $createdAt;
    protected $createdBy;

    protected $updatedAt;

    public function __construct()
    {
        $this->exerciseVariants = new ArrayCollection();
    }

    public function getId()
    {
        return (string)$this->id;
    }

    /**
     * @param string $name
     * @param string $description
     * @param bool $isCardio
     * @return Exercise
     */
    public static function create(string $name, string $description, $isCardio = false)
    {
        $entity = new self();
        $entity->name = $name;
        $entity->description = $description;
        $entity->isCardio = $isCardio;

        return $entity;
    }

    /**
     * @param $name
     * @param $description
     * @return $this
     */
    public function addExerciseVariant($name, $description)
    {
        $entity = ExerciseVariant::create($name, $description, $this);
        $this->exerciseVariants[] = $entity;

        return $this;
    }

    /**
     * @return ArrayCollection|ExerciseVariant[]
     */
    public function getExerciseVariants()
    {
        return $this->exerciseVariants;
    }

    public function getName()
    {
        return $this->name;
    }
}