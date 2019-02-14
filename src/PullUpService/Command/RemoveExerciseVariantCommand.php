<?php

namespace PullUpService\Command;

use JMS\Serializer\Annotation\Type;

class RemoveExerciseVariantCommand
{
    /**
     * @Type("string")
     */
    public $variantId;

    /**
     * @Type("string")
     */
    public $exerciseId;
}
