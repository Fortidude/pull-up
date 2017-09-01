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