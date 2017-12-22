<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class UpdateSettingsCommand
{
    /**
     * @Type("int")
     */
    public $daysPerCircuit;

    /**
     * @Type("string")
     */
    public $name;

    /**
     * @Type("string")
     */
    public $email;
}
