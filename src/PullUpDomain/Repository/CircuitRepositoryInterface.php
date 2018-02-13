<?php

namespace PullUpDomain\Repository;

use PullUpDomain\Entity\User;
use PullUpDomain\Entity\Circuit;

interface CircuitRepositoryInterface
{
    public function getByUserAndDate(User $user, \DateTime $dateTime);

    /**
     * @param User $user
     * @param array $exceptIds
     * @return Circuit|null
     */
    public function getLastCircuit(User $user, $exceptIds = []);

    /**
     * @param User $user
     * @param array $exceptIds
     * @return Circuit[]
     */
    public function getAllFuture(User $user, $exceptIds = []);
    public function getCollisions(Circuit $circuit);

    /**
     * @return Circuit[]
     */
    public function getEndedYesterday();

    public function add(Circuit $entity);
    public function remove(Circuit $entity);

    public function flush();
}