<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class PasswordRemindKeyValidateCommand
{
    /**
     * @Type("string")
     */
    public $email;

    /**
     * @Type("string")
     */
    public $key;
}
