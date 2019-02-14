<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class CreateExerciseVariantCommand
{
    /**
     * @Type("string")
     */
    public $name;

    /**
     * @Type("string")
     */
    public $exerciseId;
}
