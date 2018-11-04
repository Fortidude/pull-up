<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class PasswordChangeCommand
{
    /**
     * @Type("string")
     */
    public $password;

    /**
     * @Type("string")
     */
    public $key;

    /**
     * @Type("string")
     */
    public $email;
}
