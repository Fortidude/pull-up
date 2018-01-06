<?php

namespace PullUpDomain\Repository;

use PullUpDomain\Entity\Section;
use PullUpDomain\Entity\User;

interface SectionRepositoryInterface
{
    /**
     * @param $id
     * @return Section|null
     */
    public function getById($id);

    /**
     * @param User $user
     * @param string $id
     * @return Section|null
     */
    public function getByUserAndId(User $user, string $id);
    /**
     * @param User $user
     * @param string $name
     * @return Section|null
     */
    public function getByUserAndName(User $user, string $name);

    /**
     * @param User $user
     * @return Section[]
     */
    public function getByUser(User $user);

    public function add(Section $entity);
    public function remove(Section $entity);

    public function flush();
}
