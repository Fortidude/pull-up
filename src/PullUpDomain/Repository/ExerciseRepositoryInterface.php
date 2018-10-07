<?php

namespace PullUpDomain\Repository;

use PullUpDomain\Entity\Exercise;
use PullUpDomain\Entity\User;

interface ExerciseRepositoryInterface
{
    /**
     * @return Exercise[]
     */
    public function getList();

    /**
     * @param User $user
     * @return Exercise[]
     */
    public function getListByUser(User $user);

    /**
     * @param string $string
     * @return Exercise
     */
    public function getByNameOrId(string $string);

    public function add(Exercise $entity);
    public function remove(Exercise $entity);

    public function flush();
}