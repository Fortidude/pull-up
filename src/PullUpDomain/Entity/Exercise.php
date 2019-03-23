<?php

namespace PullUpDomain\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Exercise
{
    protected $id;

    protected $name;
    protected $description;
    protected $isCardio;

    protected $exerciseVariants;

    protected $translations;

    protected $createdAt;
    protected $createdBy;

    protected $updatedAt;

    public function __construct()
    {
        $this->exerciseVariants = new ArrayCollection();
    }

    public function getId()
    {
        return (string) $this->id;
    }

    /**
     * @param string $name
     * @param string $description
     * @param bool $isCardio
     * @return Exercise
     */
    public static function create(string $name, string $description, $isCardio = false)
    {
        $entity = new self();
        $entity->name = $name;
        $entity->description = $description;
        $entity->isCardio = $isCardio;

        return $entity;
    }

    /**
     * @param $name
     * @param $description
     * @param $isCardio
     * @return $this
     */
    public function update($name, $description, $isCardio)
    {
        $this->name = $name;
        $this->description = $description;
        $this->isCardio = $isCardio;

        return $this;
    }

    /**
     * @param $name
     * @param $description
     * @return $this
     */
    public function addExerciseVariant($name, $description)
    {
        $entity = ExerciseVariant::create($name, $description, $this);
        $this->exerciseVariants[] = $entity;

        return $this;
    }

    /**
     * @param ExerciseVariant $exerciseVariant
     * @return $this
     */
    public function removeExerciseVariant(ExerciseVariant $exerciseVariant)
    {
        $this->exerciseVariants->removeElement($exerciseVariant);

        return $this;
    }

    /**
     * @return ArrayCollection|ExerciseVariant[]
     */
    public function getExerciseVariants()
    {
        return $this->exerciseVariants;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Translations\ExerciseTranslation[]
     */
    public function getTranslations()
    {
        if ($this->translations === 'null' || !$this->translations) {
            return [];
        }

        $collection = [];
        foreach ($this->translations as $locale => $translation) {
            $name = $translation['name'];
            $description = array_key_exists("description", $translation) ? $translation['description'] : "";
            $collection[] = new Translations\ExerciseTranslation($name, $description, $locale);
        }

        return $collection;
    }
}
