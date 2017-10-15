<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class DisableGoalCommand
{
    /**
     * @Type("string")
     */
    public $id;
}
