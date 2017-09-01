<?php

namespace PullUpDomain\Entity;

class Exercise
{
    protected $id;

    protected $name;

    protected $createdAt;
    protected $createdBy;

    protected $updatedAt;

    public function getId()
    {
        return (string)$this->id;
    }

    /**
     * @param $name
     * @return Exercise
     */
    public static function create($name)
    {
        $entity = new self();
        $entity->name = $name;

        return $entity;
    }
}