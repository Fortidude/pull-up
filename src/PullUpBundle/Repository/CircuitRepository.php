<?php

namespace PullUpBundle\Repository;

use Doctrine\ORM\EntityManager;

use PullUpDomain\Entity\Circuit;
use PullUpDomain\Entity\User;
use PullUpDomain\Repository\CircuitRepositoryInterface;

class CircuitRepository extends AbstractRepository implements CircuitRepositoryInterface
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
    }

    public function getByUserAndDate(User $user, \DateTime $dateTime)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('c')
            ->from('PullUpDomainEntity:Circuit', 'c')
            ->where('c.user = :userId')
            ->andWhere('c.startAt < :date AND c.endAt > :date')
            ->setParameter('userId', $user->getId())
            ->setParameter('date', $dateTime)
            ->setMaxResults(1)
            ->getQuery();

        return $query
            ->getOneOrNullResult();
    }

    public function getLastCircuit(User $user, $exceptIds = [])
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('c')
            ->from('PullUpDomainEntity:Circuit', 'c')
            ->where('c.user = :userId');

        if ($exceptIds) {
            $query = $query
                ->andWhere('c.id NOT IN (:exceptIds)')
                ->setParameter('exceptIds', $exceptIds);
        }

        $query = $query
            ->setParameter('userId', $user->getId())
            ->orderBy('c.endAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery();

        return $query
            ->getOneOrNullResult();
    }

    /**
     * @param User $user
     * @param array $exceptIds
     * @return Circuit[]
     */
    public function getAllFuture(User $user, $exceptIds = [])
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('c')
            ->from('PullUpDomainEntity:Circuit', 'c')
            ->where('c.user = :userId');

        if ($exceptIds) {
            $query = $query
                ->andWhere('c.id NOT IN (:exceptIds)')
                ->setParameter('exceptIds', $exceptIds);
        }

        $query = $query
            ->andWhere('c.startAt > :now')
            ->setParameter('userId', $user->getId())
            ->setParameter('now', new \DateTime("now"))
            ->orderBy('c.endAt', 'DESC')
            ->getQuery();

        return $query
            ->getResult();
    }

    public function getCollisions(Circuit $circuit)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('c')
            ->from('PullUpDomainEntity:Circuit', 'c')
            ->where('c.user = :userId')
            ->andWhere('(c.startAt < :start AND c.endAt > :start) OR (c.startAt < :end AND c.endAt > :end) OR (c.startAt BETWEEN :start AND :end) OR (c.endAt BETWEEN :start AND :end)')
            ->andWhere('c.id != :id')
            ->setParameter('userId', $circuit->getUser())
            ->setParameter('start', $circuit->getStartAt())
            ->setParameter('end', $circuit->getEndAt())
            ->setParameter('id', $circuit->getId())
            ->getQuery();

        return $query
            ->getResult();
    }

    /**
     * @param Circuit $entity
     */
    public function add(Circuit $entity)
    {
        $this->addEntity($entity);
    }

    /**
     * @param Circuit $entity
     */
    public function remove(Circuit $entity)
    {
        $this->removeEntity($entity);
    }
}