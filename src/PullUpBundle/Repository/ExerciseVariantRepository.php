<?php

namespace PullUpBundle\Repository;

use Doctrine\ORM\EntityManager;

use PullUpDomain\Entity\Exercise;
use PullUpDomain\Entity\ExerciseVariant;
use PullUpDomain\Repository\ExerciseVariantRepositoryInterface;

class ExerciseVariantRepository extends AbstractRepository implements ExerciseVariantRepositoryInterface
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
    }

    /**
     * @return ExerciseVariant[]
     */
    public function getList()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('e')
            ->from('PullUpDomainEntity:ExerciseVariant', 'ev')
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param Exercise $exercise
     * @return ExerciseVariant[]
     */
    public function getListByExercise(Exercise $exercise)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('ev, e')
            ->from('PullUpDomainEntity:ExerciseVariant', 'ev')
            ->join('ev.exercise', 'e')
            ->where('ev.exercise = :exercise')
            ->setParameter('exercise', $exercise)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param ExerciseVariant $entity
     * @throws \Doctrine\DBAL\Exception\UniqueConstraintViolationException
     */
    public function add(ExerciseVariant $entity)
    {
        $this->addEntity($entity);
    }

    /**
     * @param ExerciseVariant $entity
     */
    public function remove(ExerciseVariant $entity)
    {
        $this->removeEntity($entity);
    }
}