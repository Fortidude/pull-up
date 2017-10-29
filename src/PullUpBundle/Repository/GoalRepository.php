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

    public function getByUserAndId(User $user, $id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('g')
            ->from('PullUpDomainEntity:Goal', 'g')
            ->where('g.id = :goalId')
            ->andWhere('g.user = :userId')
            ->setParameter('goalId', $id)
            ->setParameter('userId', $user->getId())
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    public function getPlannerByUser(User $user) : array
    {
        $results = [
            'today' => [],
            'yesterday' => [],
            'three_days_ago' => [],
            'week_ago' => [],
            'circuit_ago' => [],
            'older' => []
        ];
        $entities = $this->getListByUserQB($user)
            ->addOrderBy('g.lastSetAdded', 'DESC')
            ->addOrderBy('g.updatedAt', 'DESC')
            ->getQuery()->getResult();

        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        $todayEvening = clone $today;
        $todayEvening->setTime(23, 59, 59);
        $yesterday = clone $today;
        $yesterday->sub(new \DateInterval("P1D"));
        $threeDaysAgo = clone $today;
        $threeDaysAgo->sub(new \DateInterval("P3D"));
        $weekAgo = clone $today;
        $weekAgo->sub(new \DateInterval("P7D"));

        $circuitAgo = null;
        if ($user->getDaysPerCircuit() > 7) {
            $circuitAgo = clone $today;
            $circuitAgo->sub(new \DateInterval("P{$user->getDaysPerCircuit()}D"));
        }

        foreach ($entities as $entity) {
            if ($entity->wasUpdatedBetween($todayEvening, $today)) {
                $results['today'][] = $entity;
            } elseif ($entity->wasUpdatedBetween($today, $yesterday)) {
                $results['yesterday'][] = $entity;
            } elseif ($entity->wasUpdatedBetween($yesterday, $threeDaysAgo)) {
                $results['three_days_ago'][] = $entity;
            } elseif ($entity->wasUpdatedBetween($threeDaysAgo, $weekAgo)) {
                $results['week_ago'][] = $entity;
            } elseif ($circuitAgo && $entity->wasUpdatedBetween($weekAgo, $circuitAgo)) {
                $results['circuit_ago'][] = $entity;
            } else {
                $results['older'][] = $entity;
            }
        }

        return $results;
    }

    private function getListByUserQB(User $user)
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('g, e, ev, s')
            ->from('PullUpDomainEntity:Goal', 'g')
            ->leftJoin('g.exercise', 'e')
            ->leftJoin('g.exerciseVariant', 'ev')
            ->leftJoin('g.sets', 's')
            ->where('g.user = :userId')
            ->andWhere('g.removed = false')
            ->setParameter('userId', $user->getId());
    }

    /**
     * @param User $user
     * @return Goal[]
     */
    public function getListByUser(User $user)
    {
        $query = $this->getListByUserQB($user)
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