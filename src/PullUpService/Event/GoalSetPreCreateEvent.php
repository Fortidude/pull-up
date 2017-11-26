<?php

namespace PullUpService\Event;

class GoalSetPreCreateEvent
{
    protected $userId;
    protected $date;

    public function __construct($userId, \DateTime $dateTime)
    {
        $this->userId = $userId;
        $this->date = $dateTime;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }
}
