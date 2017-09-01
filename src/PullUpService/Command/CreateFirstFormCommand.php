<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class CreateFirstFormCommand
{
    /**
     * @Type("string")
     */
    public $data;
}
