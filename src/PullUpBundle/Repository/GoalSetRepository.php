<?php

namespace PullUpBundle\Repository;

use Doctrine\ORM\EntityManager;
use PullUpDomain\Entity\GoalSet;
use PullUpDomain\Entity\User;
use PullUpDomain\Repository\GoalSetRepositoryInterface;

class GoalSetRepository extends AbstractRepository implements GoalSetRepositoryInterface
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
    }

    /**
     * @param $id
     * @return GoalSet
     */
    public function getById($id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('g')
            ->from('PullUpDomainEntity:GoalSet', 'g')
            ->where('g.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    public function getLastByUser(User $user)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('g, partial goal.{id}, partial exercise.{id, name}')
            ->from('PullUpDomainEntity:GoalSet', 'g')
            ->leftJoin('g.goal', 'goal')
            ->leftJoin('goal.exercise', 'exercise')
        // ->leftJoin('g.circuit', 'circuit')
            ->where('g.user = :user')
            ->setParameter('user', $user)
            ->orderBy('g.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * @param User $user,
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     * @return GoalSet[]
     */
    public function getByUserAndDatePeriod(User $user, \DateTime $fromDate, \DateTime $toDate)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('g, goal, exercise, exerciseVariant, circuit')
            ->from('PullUpDomainEntity:GoalSet', 'g')
            ->leftJoin('g.goal', 'goal')
            ->leftJoin('goal.exercise', 'exercise')
            ->leftJoin('goal.exerciseVariant', 'exerciseVariant')
            ->leftJoin('g.circuit', 'circuit')
            ->where('g.user = :user')
            ->andWhere('g.date BETWEEN :fromDate AND :toDate')
            ->setParameter('user', $user)
            ->setParameter('fromDate', $fromDate)
            ->setParameter('toDate', $toDate)
            ->orderBy('g.createdAt', 'DESC')
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param GoalSet $entity
     */
    public function add(GoalSet $entity)
    {
        $this->addEntity($entity);
    }

    /**
     * @param GoalSet $entity
     */
    public function remove(GoalSet $entity)
    {
        $this->removeEntity($entity);
    }
}
