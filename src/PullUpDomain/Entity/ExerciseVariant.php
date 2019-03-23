<?php

namespace PullUpDomain\Entity;

class ExerciseVariant
{
    protected $id;

    protected $name;
    protected $description;

    protected $exercise;
    
    protected $translations;

    protected $createdAt;
    protected $createdBy;

    protected $updatedAt;

    protected $removed;

    public function getId()
    {
        return (string)$this->id;
    }

    /**
     * @param string $name
     * @param string $description
     * @param Exercise $exercise
     * @return ExerciseVariant
     */
    public static function create($name, $description, Exercise $exercise)
    {
        $entity = new self();
        $entity->name = $name;
        $entity->description = $description;
        $entity->exercise = $exercise;
        $entity->removed = false;

        return $entity;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function update($name)
    {
        $this->name = $name;
    }

    /**
     *
     */
    public function remove()
    {
        $this->removed = true;
    }

    /**
     * @return Translations\ExerciseVariantTranslation[]
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
            $collection[] = new Translations\ExerciseVariantTranslation($name, $description, $locale);
        }

        return $collection;
    }
}