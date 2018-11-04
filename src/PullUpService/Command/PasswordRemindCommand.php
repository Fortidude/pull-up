<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class PasswordRemindCommand
{
    /**
     * @Type("string")
     */
    public $email;
}
