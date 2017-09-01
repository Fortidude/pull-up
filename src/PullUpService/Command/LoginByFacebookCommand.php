<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class LoginByFacebookCommand
{
    /**
     * @Type("string")
     */
    public $accessToken;
}
