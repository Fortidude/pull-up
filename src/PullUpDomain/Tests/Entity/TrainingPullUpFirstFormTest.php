<?php

namespace PullUpDomain\Tests\Entity;

use PHPUnit\Framework\TestCase;

use PullUpDomain\Entity\TrainingPullUpFirstForm as FirstForm;

class TrainingPullUpFirstFormTest extends TestCase
{
    public function setUp()
    {
    }

    public function testCreate()
    {
        $data = [
            "age" => "26",
            "weight" => "72",
            "body_fat" => "3",
            "practice_already" => true,
            "practice_long" => "3",
            "frequency_training" => 0,
            "can_do_pull_up" => true,
            "resistance_pull_up" => false,
            "amount_pull_up" => "5",
            "resistance_pull_up_type" => 0,
            "resistance_pull_up_amount" => 0
        ];

        $object = FirstForm::create($data);

        $this->assertEquals($data['age'], $object->getAge());
        $this->assertEquals($data['weight'], $object->getWeight());
        $this->assertEquals($data['body_fat'], $object->getBodyFat());
        $this->assertEquals($data['practice_already'], $object->getPracticeAlready());
        $this->assertEquals($data['practice_long'], $object->getPracticeLong());
        $this->assertEquals($data['frequency_training'], $object->getFrequencyTraining());
        $this->assertEquals($data['can_do_pull_up'], $object->getCanDoPullUp());
        $this->assertEquals($data['amount_pull_up'], $object->getAmountPullUp());
        $this->assertEquals($data['resistance_pull_up'], $object->getResistancePullUp());
        $this->assertEquals($data['resistance_pull_up_type'], $object->getResistancePullUpType());
        $this->assertEquals($data['resistance_pull_up_amount'], $object->getResistancePullUpAmount());
    }
}
