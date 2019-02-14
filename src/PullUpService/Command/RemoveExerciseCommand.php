<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class RemoveExerciseCommand
{
    /**
     * @Type("string")
     */
    public $exerciseId;
}
