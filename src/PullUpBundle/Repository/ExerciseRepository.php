<?php

namespace PullUpBundle\Repository;

use Doctrine\ORM\EntityManager;

use PullUpDomain\Entity\Exercise;
use PullUpDomain\Entity\User;
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
            ->from('PullUpDomainEntity:Exercise', 'e, ev')
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param User $user
     * @return Exercise[]
     */
    public function getListByUser(User $user)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('e, ev')
            ->from('PullUpDomainEntity:Exercise', 'e')
            ->leftJoin('e.exerciseVariants', 'ev', \Doctrine\ORM\Query\Expr\Join::WITH, $qb->expr()->andX(
                $qb->expr()->eq('ev.removed', ':removed')
            ))
            ->andWhere('(e.createdBy IS NULL OR e.createdBy = :user)')
            ->setParameter('user', $user)
            ->setParameter('removed', false)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param string $name
     * @return Exercise
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByName(string $name): Exercise
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('e, ev')
            ->from('PullUpDomainEntity:Exercise', 'e')
            ->join('e.exerciseVariants', 'ev')
            ->where('e.name = :name')
            ->setParameter('name', $name)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * @param string $string
     * @return mixed|Exercise
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByNameOrId(string $string)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('e, ev')
            ->from('PullUpDomainEntity:Exercise', 'e')
            ->leftJoin('e.exerciseVariants', 'ev')
            ->where('e.name = :string OR e.id = :string')
            ->setParameter('string', $string)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * @param Exercise $entity
     * @throws \Doctrine\DBAL\Exception\UniqueConstraintViolationException
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