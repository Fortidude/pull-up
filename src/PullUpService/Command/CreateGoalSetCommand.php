<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class CreateGoalSetCommand
{
    /**
     * @Type("string")
     */
    public $goal;

    /**
     * @Type("string")
     */
    public $date;

    /**
     * @Type("int")
     */
    public $reps;

    /**
     * @Type("int")
     */
    public $weight;

    /**
     * @Type("int")
     */
    public $time;

    /**
     * @Type("int")
     */
    public $difficultyLevel;
}
