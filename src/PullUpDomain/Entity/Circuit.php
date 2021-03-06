<?php

namespace PullUpDomain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;

class Circuit
{
    protected $id;
    protected $user;
    protected $startAt;
    protected $endAt;
    protected $days;
    protected $finished = false;
    protected $sets;

    public $justCreated = false;

    public function __construct()
    {
        /**
         * @TODO ArrayCollection as interface?
         */
        $this->sets = new ArrayCollection();
    }

    public static function create(User $user)
    {
        return self::createByStartDate($user, new \DateTime("now"));
    }

    public static function createByStartDate(User $user, \DateTime $startDate)
    {
        $entity = new self();
        $entity->id = Uuid::uuid4();
        $entity->justCreated = true;
        $entity->user = $user;
        $entity->days = $user->getDaysPerCircuit();

        $entity->startAt = clone $startDate;
        $entity->startAt->setTime(0, 0, 0);

        $endAt = clone $entity->startAt;
        $endAt->add(new \DateInterval("P{$user->getDaysPerCircuit()}D"));
        $endAt->setTime(23, 59, 59);
        $entity->endAt = $endAt;

        $now = new \DateTime("now");
        if ($now > $endAt) {
            $entity->finished = true;
        }

        return $entity;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return (string)$this->id;
    }

    /**
     * @return ArrayCollection|GoalSet[]
     */
    public function getSets()
    {
        return $this->sets;
    }

    /**
     * @return bool
     */
    public function isFinished()
    {
        return $this->finished;
    }

    /**
     * @return \DateTime()
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTime $dateTime, int $duration)
    {
        $dateTime->setTime(0, 0, 0);
        $this->startAt = $dateTime;

        $this->changeDuration($duration);

        return $this;
    }

    /**
     * @return \DateTime()
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * @param \DateTime $dateTime
     * @return $this
     */
    public function setEndAt(\DateTime $dateTime)
    {
        $dateTime->setTime(23, 59, 59);
        $this->endAt = $dateTime;

        $diff = $this->endAt->diff($this->startAt);
        $this->days = $diff->d + 1;

        return $this;
    }

    /**
     * @param int $duration $startAt
     */
    public function changeDuration(int $duration)
    {
        $duration--;
        $date = clone $this->getStartAt();
        $date->add(new \DateInterval("P{$duration}D"));

        $this->setEndAt($date);
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->days;
    }

    /**
     * Collisions means we have some circuit for this user for that time period.
     * EX: This circuit is 10.05 to 20.05 and we have some from 15.05 do 25.05
     * Then this one should be set end_at to 14.05
     *
     * @param array|null $collisionCircuits
     */
    public function checkCollisions(array $collisionCircuits = null)
    {
        if ($collisionCircuits) {
            $endDate = $this->getEndAt();
            foreach ($collisionCircuits as $collisionCircuit) {
                if ($endDate > $collisionCircuit->getStartAt()) {
                    $endDate = $collisionCircuit->getStartAt();
                }
            }

            $endDate->sub(new \DateInterval("P1D"));
            $this->setEndAt($endDate);
            $this->finish();
        }
    }

    /**
     * @param GoalSet $goalSet
     * @return $this
     */
    public function addSet(GoalSet $goalSet)
    {
        $this->sets[] = $goalSet;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function isCurrent()
    {
        $now = new \DateTime();
        if ($this->startAt < $now && $this->endAt > $now && !$this->isFinished()) {
            return true;
        }

        return false;
    }

    public function isForDate(\DateTime $dateTime)
    {
        if ($this->startAt < $dateTime && $this->endAt > $dateTime) {
            return true;
        }

        return false;
    }

    public function finish()
    {
        $this->finished = true;
    }
}