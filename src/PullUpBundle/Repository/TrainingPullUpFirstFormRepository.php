<?php

namespace PullUpBundle\Repository;

use Doctrine\ORM\EntityManager;

use PullUpDomain\Entity\User;
use PullUpDomain\Entity\TrainingPullUpFirstForm as FirstForm;
use PullUpDomain\Repository\TrainingPullUpFirstFormRepositoryInterface;

class TrainingPullUpFirstFormRepository extends AbstractRepository implements TrainingPullUpFirstFormRepositoryInterface
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
    }

    /**
     * @param User $user
     * @return FirstForm
     */
    public function getOneByUser(User $user)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('f')
            ->from('PullUpDomainEntity:TrainingPullUpFirstForm', 'f')
            ->where('f.user = :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery();

        return $query
            ->getSingleResult();
    }

    /**
     * @param FirstForm $entity
     */
    public function add(FirstForm $entity)
    {
        $this->addEntity($entity);
    }

    /**
     * @param FirstForm $entity
     */
    public function remove(FirstForm $entity)
    {
        $this->removeEntity($entity);
    }
}