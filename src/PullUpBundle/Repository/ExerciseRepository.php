<?php

namespace PullUpBundle\Repository;

use Doctrine\ORM\EntityManager;

use PullUpDomain\Entity\Exercise;
use PullUpDomain\Repository\ExerciseRepositoryInterface;

class ExerciseRepository extends AbstractRepository implements ExerciseRepositoryInterface
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
    }

    /**
     * @return Exercise[]
     */
    public function getList()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('e')
            ->from('PullUpDomainEntity:Exercise', 'e')
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param Exercise $entity
     */
    public function add(Exercise $entity)
    {
        $this->addEntity($entity);
    }

    /**
     * @param Exercise $entity
     */
    public function remove(Exercise $entity)
    {
        $this->removeEntity($entity);
    }
}