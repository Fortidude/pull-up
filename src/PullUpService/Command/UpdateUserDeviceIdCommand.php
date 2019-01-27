<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class UpdateUserDeviceIdCommand
{
    /**
     * @Type("string")
     */
    public $deviceId;
}
