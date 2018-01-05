<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class CreateSectionCommand
{
    /**
     * @Type("string")
     */
    public $name;

    /**
     * @Type("string")
     */
    public $description;

}
