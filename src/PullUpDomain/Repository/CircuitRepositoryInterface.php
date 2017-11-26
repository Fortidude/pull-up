<?php

namespace PullUpDomain\Repository;

use PullUpDomain\Entity\User;
use PullUpDomain\Entity\Circuit;

interface CircuitRepositoryInterface
{
    public function getByUserAndDate(User $user, \DateTime $dateTime);
    public function getLastCircuit(User $user, $exceptIds = []);
    public function getCollisions(Circuit $circuit);

    public function add(Circuit $entity);
    public function remove(Circuit $entity);

    public function flush();
}