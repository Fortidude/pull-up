<?php

namespace PullUpBundle\Repository;

use Doctrine\ORM\EntityManager;

use PullUpDomain\Entity\User;
use PullUpDomain\Entity\TrainingPullUp;
use PullUpDomain\Repository\TrainingPullUpRepositoryInterface;

class TrainingPullUpRepository extends AbstractRepository implements TrainingPullUpRepositoryInterface
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
    }

    /**
     * @param User $user
     * @return TrainingPullUp[]
     */
    public function getListByUser(User $user)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('t')
            ->from('PullUpDomainEntity:TrainingPullUp', 't')
            ->where('t.user = :userId')
            ->setParameter('userId', $user->getId())
           // ->orderBy('t.id', 'DESC')
            ->orderBy('t.route', 'DESC')
            ->addOrderBy('t.createdAt', 'ASC')
            ->getQuery();

        return $query
            ->getResult();
    }

    /**
     * @param User $user
     * @param $route
     * @return TrainingPullUp[]
     */
    public function getListByUserAndRoute(User $user, $route)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('t')
            ->from('PullUpDomainEntity:TrainingPullUp', 't')
            ->where('t.user = :userId')
            ->andWhere('t.route = :route')
            ->orderBy('t.level', 'DESC')
            ->setParameter('userId', $user->getId())
            ->setParameter('route', $route)
            ->getQuery();

        /** @var TrainingPullUp[] $results */
        return $query->getResult();
    }

    /**
     * @return string
     */
    public function getStatisticallyHarderTrainingType()
    {
        // TODO: Implement getStatisticallyHarderTrainingType() method.
        return 'three';
    }

    public function isAlreadyDone(User $user, $route, $type)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('COUNT(t.id)')
            ->from('PullUpDomainEntity:TrainingPullUp', 't')
            ->where('t.user = :userId')
            ->andWhere('t.route = :route')
            ->andWhere('t.type = :type')
            ->setParameter('userId', $user->getId())
            ->setParameter('route', $route)
            ->setParameter('type', $type)
            ->getQuery();

        $result = $query->getSingleScalarResult();

        return (bool)$result;
    }

    /**
     * @param User $user
     * @return int
     */
    public function getLastFinishedRouteNumber(User $user)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('COUNT(t)')
            ->from('PullUpDomainEntity:TrainingPullUp', 't')
            ->where('t.user = :userId')
            ->andWhere('t.type = :type')
            ->orderBy('t.createdAt', 'DESC')
            ->setParameter('userId', $user->getId())
            ->setParameter('type', 'five')
            ->getQuery();

        return (int)$query->getSingleScalarResult();
    }

    /**
     * @param User $user
     * @return TrainingPullUp|null
     */
    public function getLastDone(User $user)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('t')
            ->from('PullUpDomainEntity:TrainingPullUp', 't')
            ->where('t.user = :userId')
            ->orderBy('t.createdAt', 'DESC')
            ->setParameter('userId', $user->getId())
            ->setMaxResults(1)
            ->getQuery();

        $results = $query->getResult();

        return isset($results[0]) ? $results[0] : null;
    }

    /**
     * @param User $user
     * @param int $route
     * @return TrainingPullUp
     */
    public function getFirstByRoute(User $user, $route)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('t')
            ->from('PullUpDomainEntity:TrainingPullUp', 't')
            ->where('t.user = :userId')
            ->andWhere('t.route = :route')
            ->andWhere('t.type = :type')
            ->orderBy('t.createdAt', 'DESC')
            ->setParameter('userId', $user->getId())
            ->setParameter('route', $route)
            ->setParameter('type', 'one')
            ->setMaxResults(1)
            ->getQuery();

        $results = $query->getResult();

        return isset($results[0]) ? $results[0] : null;
    }

    /**
     * @param TrainingPullUp $entity
     */
    public function add(TrainingPullUp $entity)
    {
        $this->addEntity($entity);
    }

    /**
     * @param TrainingPullUp $entity
     */
    public function remove(TrainingPullUp $entity)
    {
        $this->removeEntity($entity);
    }
}