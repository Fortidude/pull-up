<?php

namespace PullUpBundle\Repository;

use Doctrine\ORM\EntityManager;

use PullUpDomain\Entity\Goal;
use PullUpDomain\Entity\User;
use PullUpDomain\Repository\GoalRepositoryInterface;

class GoalRepository extends AbstractRepository implements GoalRepositoryInterface
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
    }

    /**
     * @param User $user
     * @return Goal[]
     */
    public function getListByUser(User $user)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('g')
            ->from('PullUpDomainEntity:Goal', 'g')
            ->where('g.user = :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param Goal $entity
     */
    public function add(Goal $entity)
    {
        $this->addEntity($entity);
    }

    /**
     * @param Goal $entity
     */
    public function remove(Goal $entity)
    {
        $this->removeEntity($entity);
    }
}