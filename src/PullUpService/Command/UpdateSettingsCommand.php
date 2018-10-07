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
     * User name (login)
     *
     * @Type("string")
     */
    public $name;

    /**
     * @Type("boolean")
     */
    public $plannerCustomMode;

    /**
     * @Type("string")
     */
    public $base64avatar;
}
