<?php

namespace PullUpBundle\Repository;

use Doctrine\ORM\EntityManager;

use PullUpDomain\Entity\Section;
use PullUpDomain\Entity\User;
use PullUpDomain\Repository\SectionRepositoryInterface;

class SectionRepository extends AbstractRepository implements SectionRepositoryInterface
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
    }

    /**
     * @param $id
     * @return Section|null
     */
    public function getById($id)
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('s')
            ->from('PullUpDomainEntity:Section', 's')
            ->where('s.id = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param User $user
     * @param string $id
     * @return Section|null
     */
    public function getByUserAndId(User $user, string $id)
    {
        return $this->getBaseQueryBuildierWithJoins()
            ->where('s.user = :userId')
            ->andWhere('s.id = :id')
            ->andWhere('s.removed = false')
            ->setParameter('userId', $user->getId())
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param User $user
     * @param string $name
     * @return Section|null
     */
    public function getByUserAndName(User $user, string $name)
    {
        return $this->getBaseQueryBuildierWithJoins()
            ->where('s.user = :userId')
            ->andWhere('s.name = :name')
            ->andWhere('s.removed = false')
            ->setParameter('userId', $user->getId())
            ->setParameter('name', $name)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param User $user
     * @return Section[]
     */
    public function getByUser(User $user)
    {
        return $this->getBaseQueryBuildierWithJoins()
            ->where('s.user = :userId')
            ->andWhere('s.removed = false')
            ->setParameter('userId', $user->getId())
            ->addOrderBy('g.lastSetAdded', 'DESC')
            ->addOrderBy('g.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Section $entity
     */
    public function add(Section $entity)
    {
        $this->addEntity($entity);
    }

    /**
     * @param Section $entity
     */
    public function remove(Section $entity)
    {
        $this->removeEntity($entity);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getBaseQueryBuildierWithJoins()
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('s, g, sets, e, ev')
            ->from('PullUpDomainEntity:Section', 's')
            ->leftJoin('s.goals', 'g')
            ->leftJoin('g.exercise', 'e')
            ->leftJoin('g.exerciseVariant', 'ev')
            ->leftJoin('g.sets', 'sets');
    }
}