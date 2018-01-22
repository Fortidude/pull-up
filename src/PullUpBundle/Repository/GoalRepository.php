<?php

namespace PullUpBundle\Repository;

use Doctrine\ORM\EntityManager;

use PullUpBundle\Service\Statistics;

use PullUpDomain\Entity\Exercise;
use PullUpDomain\Entity\ExerciseVariant;
use PullUpDomain\Entity\Goal;
use PullUpDomain\Entity\User;
use PullUpDomain\Repository\GoalRepositoryInterface;
use PullUpDomain\Repository\Response\GoalStatisticsResponse;

class GoalRepository extends AbstractRepository implements GoalRepositoryInterface
{
    /** @var Statistics\ByUserAndGoals */
    protected $statsByGoalsService;

    public function __construct(EntityManager $em, Statistics\ByUserAndGoals $statsByGoalsService)
    {
        $this->statsByGoalsService = $statsByGoalsService;
        parent::__construct($em);
    }

    public function getById($id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('g')
            ->from('PullUpDomainEntity:Goal', 'g')
            ->where('g.id = :goalId')
            ->setParameter('goalId', $id)
            ->getQuery();

        return $query->getOneOrNullResult();
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

    public function getPlannerByUser(User $user): array
    {
        /**
         * @TODO KEYS CREATED BY USER?
         */

        return [];
    }

    public function getCalendarPlannerByUser(User $user) : array
    {
        $results = [
            'today' => [],
            'yesterday' => [],
            'two_days_ago' => [],
            'three_days_ago' => [],
            'four_days_ago' => [],
            'five_days_ago' => [],
            'six_days_ago' => [],
            'week_ago' => [],
            'older_than_week_ago' => [],
            'circuit_ago' => [],
            'older' => []
        ];

        /** @var Goal[] $entities */
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

        $twoDaysAgo = clone $today;
        $twoDaysAgo->sub(new \DateInterval("P2D"));

        $threeDaysAgo = clone $today;
        $threeDaysAgo->sub(new \DateInterval("P3D"));

        $fourDaysAgo = clone $today;
        $fourDaysAgo->sub(new \DateInterval("P4D"));

        $fiveDaysAgo = clone $today;
        $fiveDaysAgo->sub(new \DateInterval("P5D"));

        $sixDaysAgo = clone $today;
        $sixDaysAgo->sub(new \DateInterval("P7D"));

        $weekAgo = clone $today;
        $weekAgo->sub(new \DateInterval("P7D"));

        if ($user->getDaysPerCircuit() > 7) {
            $circuitAgo = clone $today;
            $circuitAgo->sub(new \DateInterval("P{$user->getDaysPerCircuit()}D"));
        }

        $circuitAgoDate = null;
        $twoCircuitAgoDate = null;
        if ($user->getDaysPerCircuit() > 7) {
            $circuitAgoDate = clone $today;
            $circuitAgoDate->sub(new \DateInterval("P{$user->getDaysPerCircuit()}D"));

            $twoCircuitAgoDate = clone $circuitAgoDate;
            $twoCircuitAgoDate->sub(new \DateInterval("P{$user->getDaysPerCircuit()}D"));
        }

        foreach ($entities as $entity) {
            if ($entity->wasUpdatedBetween($todayEvening, $today)) {
                $results['today'][] = $entity;
            } elseif ($entity->wasUpdatedBetween($today, $yesterday)) {
                $results['yesterday'][] = $entity;
            } elseif ($entity->wasUpdatedBetween($yesterday, $threeDaysAgo)) {
                $results['three_days_ago'][] = $entity;
            } elseif ($entity->wasUpdatedBetween($threeDaysAgo, $fourDaysAgo)) {
                $results['four_days_ago'][] = $entity;
            } elseif ($entity->wasUpdatedBetween($fourDaysAgo, $fiveDaysAgo)) {
                $results['five_days_ago'][] = $entity;
            } elseif ($entity->wasUpdatedBetween($fiveDaysAgo, $sixDaysAgo)) {
                $results['six_days_ago'][] = $entity;
            } elseif ($entity->wasUpdatedBetween($sixDaysAgo, $weekAgo)) {
                $results['week_ago'][] = $entity;
            } elseif ($circuitAgoDate && $entity->wasUpdatedBetween($weekAgo, $circuitAgoDate)) {
                $results['older_than_week_ago'][] = $entity;
            } elseif ($circuitAgoDate && $twoCircuitAgoDate && $entity->wasUpdatedBetween($circuitAgoDate, $twoCircuitAgoDate)) {
                $results['circuit_ago'][] = $entity;
            } else {
                $results['older'][] = $entity;
            }
        }

        if (!$results['older_than_week_ago']) {
            unset($results['older_than_week_ago']);
        }
        
        if (!$results['circuit_ago']) {
            unset($results['circuit_ago']);
        }

        return $results;
    }

    /**
     * @param User $user
     * @return \Doctrine\ORM\QueryBuilder
     */
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

    public function checkIfDuplicate(User $user, Exercise $exercise, ExerciseVariant $variant = null)
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('g, e')
            ->from('PullUpDomainEntity:Goal', 'g')
            ->leftJoin('g.exercise', 'e')
            ->where('g.user = :userId')
            ->andWhere('g.exercise = :exercise');

        if ($variant instanceof ExerciseVariant) {
            $qb = $qb
                ->addSelect('ev')
                ->leftJoin('g.exerciseVariant', 'ev')
                ->andWhere('g.exerciseVariant = :variant')
                ->setParameter('variant', $variant);
        } else {
            $qb = $qb
                ->andWhere('g.exerciseVariant IS NULL');
        }

        return $qb = $qb
            ->setParameter('userId', $user->getId())
            ->setParameter('exercise', $exercise)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param User $user
     * @return GoalStatisticsResponse
     */
    public function getStatistics(User $user) : GoalStatisticsResponse
    {
        $currentCircle = $user->getCurrentTrainingCircuit();
        $lastCircuit = $user->getTrainingCircuitByDate($currentCircle->getStartAt()->sub(new \DateInterval("P2D")));

        /** @var Goal[] $allGoals */
        $currentCircleGoals = $this->getListByUserQB($user)
            ->addSelect('circuit')
            ->leftJoin('s.circuit', 'circuit')
            ->andWhere('s.circuit = :circuitEntity')
            ->setParameter('circuitEntity', $currentCircle)
            ->addOrderBy('g.lastSetAdded', 'DESC')
            ->addOrderBy('g.updatedAt', 'DESC')
            ->getQuery()->getResult();

        /** @var Goal[] $allGoals */
        $lastCircleGoals = $this->getListByUserQB($user)
            ->addSelect('circuit')
            ->leftJoin('s.circuit', 'circuit')
            ->andWhere('s.circuit = :circuitEntity')
            ->setParameter('circuitEntity', $lastCircuit)
            ->addOrderBy('g.lastSetAdded', 'DESC')
            ->addOrderBy('g.updatedAt', 'DESC')
            ->getQuery()->getResult();

        /** @var Goal[] $allGoals */
        $allGoals = $this->getListByUserQB($user)
            ->addSelect('circuit')
            ->leftJoin('s.circuit', 'circuit')
            ->addOrderBy('g.lastSetAdded', 'DESC')
            ->addOrderBy('g.updatedAt', 'DESC')
            ->getQuery()->getResult();

        return $this->statsByGoalsService->get($currentCircleGoals, $lastCircleGoals, $allGoals);
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