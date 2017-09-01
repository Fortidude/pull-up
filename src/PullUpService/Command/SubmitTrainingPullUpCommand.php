<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class SubmitTrainingPullUpCommand
{
    /**
     * @Type("string")
     */
    public $data;
}
