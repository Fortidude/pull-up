<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class CreateTrainingPullUpFirstFormCommand
{
    /**
     * @Type("string")
     */
    public $data;
}
