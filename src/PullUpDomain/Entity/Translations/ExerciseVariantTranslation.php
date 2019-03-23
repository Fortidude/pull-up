<?php

namespace PullUpDomain\Entity\Translations;

class ExerciseVariantTranslation
{
    protected $name;
    protected $description;
    protected $locale;

    public function __construct(string $name, string $description, string $locale)
    {
        $this->name = $name;
        $this->description = $description;
        $this->locale = $locale;
    }
}
