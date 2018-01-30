<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class CreateGoalCommand
{
    /**
     * @Type("string")
     */
    public $name;

    /**
     * @Type("string")
     */
    public $description;

    /**
     * @Type("string")
     */
    public $exercise;

    /**
     * @Type("string")
     */
    public $exerciseVariant;

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

    /**
     * @Type("int")
     */
    public $noSpecifiedGoal;

    /**
     * @Type("string")
     */
    public $section;
}
