<?php

namespace PullUpService\Event;

class UserDurationPerCircuitChangedEvent
{
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
