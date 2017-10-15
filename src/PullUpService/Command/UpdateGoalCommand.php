<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class UpdateGoalCommand
{
    /**
     * @Type("string")
     */
    public $id;

    /**
     * @Type("int")
     */
    public $sets;

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
}
