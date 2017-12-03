<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class UpdateSettingsCommand
{
    /**
     * @Type("int")
     */
    public $daysPerCircuit;
}
