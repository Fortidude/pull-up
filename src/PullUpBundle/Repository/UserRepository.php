<?php

namespace PullUpBundle\Repository;

use Doctrine\ORM\EntityManager;
use PullUpDomain\Entity\User;
use PullUpDomain\Repository\UserRepositoryInterface;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
    }

    /**
     * @param int $id
     * @return User
     */
    public function getOne($id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('u')
            ->from('PullUpDomainEntity:User', 'u')
            ->where('u.id = :id')
        //->andWhere('u.enabled = 1')
            ->setParameter('id', $id)
            ->getQuery();

        return $query
            ->getSingleResult();
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function findOneBy(array $params)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb = $qb->select('u')
            ->from('PullUpDomainEntity:User', 'u')

            ->where('u.enabled = 1')
            ->andWhere('u.expiresAt > :now')
            ->setParameter('now', new \DateTime('now'));

        foreach ($params as $param => $value) {
            $qb = $qb
                ->andWhere("u.{$param} = :{$param}")
                ->setParameter($param, $value);
        }

        $query = $qb
            ->getQuery();

        return $query
            ->getOneOrNullResult();
    }

    public function getUsersWithNotifications()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb = $qb->select('u')
            ->from('PullUpDomainEntity:User', 'u')
            ->where('u.enabled = 1')
            ->andWhere('u.amazonArn IS NOT NULL');

        $query = $qb
            ->getQuery();

        return $query
            ->getResult();
    }

    /**
     * @param array $names
     * @param array $excluded
     * @return bool
     */
    public function checkIfExist(array $names, array $excluded = []): bool
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('u')
            ->from('PullUpDomainEntity:User', 'u')
            ->where('(u.username IN (:names) OR u.email IN (:names))')
            ->setParameter('names', $names);

        if ($excluded) {
            $query = $query
                ->andWhere('u.id NOT IN (:ids)')
                ->setParameter('ids', $excluded);
        }

        $result = $query
            ->getQuery()
            ->getOneOrNullResult();

        if ($result && $result instanceof User) {
            return true;
        }

        return false;
    }

    /**
     * @param User $user
     */
    public function add(User $user)
    {
        $this->addEntity($user);
    }

    /**
     * @param User $user
     */
    public function remove(User $user)
    {
        $this->removeEntity($user);
    }
}
