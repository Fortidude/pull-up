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

    protected $removed;

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
        $entity->removed = false;

        return $entity;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function update($name)
    {
        $this->name = $name;
    }

    /**
     *
     */
    public function remove()
    {
        $this->removed = true;
    }
}