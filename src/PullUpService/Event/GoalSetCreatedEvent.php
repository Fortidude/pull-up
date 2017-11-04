<?php

namespace PullUpService\Event;

class GoalSetCreatedEvent
{
    protected $setId;

    public function __construct($setId)
    {
        $this->setId = $setId;
    }

    public function getSetId()
    {
        return $this->setId;
    }
}
