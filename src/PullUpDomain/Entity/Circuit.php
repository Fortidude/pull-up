<?php

namespace PullUpDomain\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Circuit
{
    protected $id;
    protected $user;
    protected $startAt;
    protected $endAt;
    protected $days;
    protected $finished = false;
    protected $sets;

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
     * Collisions means we have some circuit for this user for that time period.
     * EX: This circuit is 10.05 to 20.05 and we have some from 15.05 do 25.05
     * Then this one should be set end_at to 14.05
     *
     * @param array|null $collisionCircuits
     */
    public function checkCollisions(array $collisionCircuits = null)
    {
        if ($collisionCircuits) {
            $date = $this->getEndAt();
            foreach ($collisionCircuits as $collisionCircuit) {
                if ($date > $collisionCircuit->getStartAt()) {
                    $date = $collisionCircuit->getStartAt();
                }
            }

            $date->sub(new \DateInterval("P1D"));
            $this->setEndAt($date);
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