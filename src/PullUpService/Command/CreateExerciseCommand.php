<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class CreateExerciseCommand
{
    /**
     * @Type("string")
     */
    public $name;

    /**
     * @Type("string")
     */
    public $variantName;

}
