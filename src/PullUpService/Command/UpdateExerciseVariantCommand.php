<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class UpdateExerciseVariantCommand
{

    /**
     * @Type("string")
     */
    public $variantId;

    /**
     * @Type("string")
     */
    public $name;

    /**
     * @Type("string")
     */
    public $exerciseId;
}
