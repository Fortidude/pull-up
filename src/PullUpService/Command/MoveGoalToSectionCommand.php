<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class MoveGoalToSectionCommand
{
    /**
     * @Type("string")
     */
    public $goalId;

    /**
     * @Type("string")
     */
    public $sectionId;
}
