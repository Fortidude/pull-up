<?php

namespace PullUpDomain\Entity;

class FirstForm
{
    protected $id;
    protected $user;

    protected $age;
    protected $weight;
    protected $bodyFat;
    protected $practiceAlready;
    protected $practiceLong;
    protected $frequencyTraining;

    protected $canDoPullUp;
    protected $amountPullUp;

    protected $resistancePullUp;
    protected $resistancePullUpType;
    protected $resistancePullUpAmount;

    protected $createdAt;
    protected $updatedAt;

    public function getId()
    {
        return (string)$this->id;
    }

    /**
     * @param array $data
     * @return FirstForm
     */
    public static function create(array $data)
    {
        $entity = new self();
        foreach ($data as $key => $value) {
            $property = str_replace('_', '', lcfirst(ucwords($key, '_')));

            if (property_exists($entity, $property)) {
                $entity->{$property} = $value;
            }
        }

        return $entity;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return mixed
     */
    public function getBodyFat()
    {
        return $this->bodyFat;
    }

    /**
     * @return mixed
     */
    public function getPracticeAlready()
    {
        return $this->practiceAlready;
    }

    /**
     * @return mixed
     */
    public function getPracticeLong()
    {
        return $this->practiceLong;
    }

    /**
     * @return mixed
     */
    public function getFrequencyTraining()
    {
        return $this->frequencyTraining;
    }

    /**
     * @return mixed
     */
    public function getCanDoPullUp()
    {
        return $this->canDoPullUp;
    }

    /**
     * @return mixed
     */
    public function getAmountPullUp()
    {
        return $this->amountPullUp;
    }

    /**
     * @return mixed
     */
    public function getResistancePullUp()
    {
        return $this->resistancePullUp;
    }

    /**
     * @return mixed
     */
    public function getResistancePullUpType()
    {
        return $this->resistancePullUpType;
    }

    /**
     * @return mixed
     */
    public function getResistancePullUpAmount()
    {
        return $this->resistancePullUpAmount;
    }
}