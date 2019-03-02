<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class AssignUserToPlanCommand
{
    /**
     * @Type("string")
     */
    public $plan;
}
