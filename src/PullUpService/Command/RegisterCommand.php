<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class RegisterCommand
{
    /**
     * @Type("string")
     */
    public $username;

    /**
     * @Type("string")
     */
    public $password;
}
