<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class UpdateExerciseCommand
{
    /**
     * @Type("string")
     */
    public $id;

    /**
     * @Type("string")
     */
    public $name;

    /**
     * @Type("string")
     */
    public $description;

    /**
     * @Type("boolean")
     */
    public $isCardio;

}
