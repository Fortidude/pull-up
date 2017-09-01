<?php

namespace PullUpDomain\Repository;

use PullUpDomain\Entity\Exercise;

interface ExerciseRepositoryInterface
{
    /**
     * @return Exercise[]
     */
    public function getList();

    public function add(Exercise $entity);
    public function remove(Exercise $entity);
}