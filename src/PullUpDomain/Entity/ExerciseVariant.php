<?php

namespace PullUpDomain\Entity;

class ExerciseVariant
{
    protected $id;

    protected $name;
    protected $description;

    protected $exercise;

    protected $createdAt;
    protected $createdBy;

    protected $updatedAt;

    public function getId()
    {
        return (string)$this->id;
    }

    /**
     * @param string $name
     * @param string $description
     * @param Exercise $exercise
     * @return ExerciseVariant
     */
    public static function create($name, $description, Exercise $exercise)
    {
        $entity = new self();
        $entity->name = $name;
        $entity->description = $description;
        $entity->exercise = $exercise;

        return $entity;
    }
}