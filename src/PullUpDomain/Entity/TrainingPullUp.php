<?php

namespace PullUpDomain\Entity;

class TrainingPullUp
{
    protected $id;
    protected $user;
    
    protected $route;
    protected $type;
    protected $reps;
    protected $level;

    protected $additionalInformation;

    protected $createdAt;
    protected $updatedAt;
    
    public static function create(User $user, $route, $type, $level, $reps, $additionalInformation = [])
    {
        $entity = new self();
        $entity->user = $user;
        $entity->route = $route;
        $entity->type = $type;
        $entity->level = $level;
        $entity->reps = $reps;
        $entity->additionalInformation = $additionalInformation;
        $entity->createdAt = new \DateTime('NOW');

        return $entity;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return integer
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return integer
     */
    public function getReps()
    {
        return $this->reps;
    }

    /**
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param \DateInterval $interval
     * @return bool
     * @throws \Exception
     */
    public function isNextAvailable(\DateInterval $interval)
    {
        if (!($this->getCreatedAt() instanceof \DateTime)) {
            throw new \Exception("missing created_at. Make sure this entity is persisted.");
        }

        $now = new \DateTime("NOW");
        return $this->getCreatedAt() <= $now->sub($interval);
    }
}