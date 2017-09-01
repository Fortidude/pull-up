<?php

namespace PullUpDomain\Repository;

use PullUpDomain\Entity\User;

interface UserRepositoryInterface
{
    /**
     * @param $id
     * @return User
     */
    public function getOne($id);

    /**
     * @param array $params
     * @return User
     */
    public function findOneBy(array $params);

    public function add(User $user);
    public function remove(User $user);
}