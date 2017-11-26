<?php

namespace PullUpDomain\Repository;

use PullUpDomain\Entity\Exercise;

interface ExerciseRepositoryInterface
{
    /**
     * @return Exercise[]
     */
    public function getList();

    /**
     * @param string $string
     * @return Exercise
     */
    public function getByNameOrId(string $string);

    public function add(Exercise $entity);
    public function remove(Exercise $entity);

    public function flush();
}