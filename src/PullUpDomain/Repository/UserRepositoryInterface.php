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

    /**
     * @param array $names
     * @param array $excluded
     * @return bool
     */
    public function checkIfExist(array $names, array $excluded = []): bool;

    /**
     * @return User[]
     */
    public function getUsersWithNotifications();

    public function add(User $user);
    public function remove(User $user);

    public function flush();
}
