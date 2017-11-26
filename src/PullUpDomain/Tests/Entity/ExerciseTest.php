<?php

namespace PullUpDomain\Tests\Entity;

use PHPUnit\Framework\TestCase;

use PullUpDomain\Entity\Exercise;
use PullUpDomain\Entity\ExerciseVariant;
use PullUpDomain\Entity\User;

class ExerciseTest extends TestCase
{
    public function setUp()
    {
    }

    public function testCreate()
    {
        $name = "test";
        $description = "description";

        $entity = Exercise::create($name, $description);
        $entity->addExerciseVariant($name, $description);

        $this->assertEquals($entity->getName(), $name);
        $this->assertArrayHasKey(0, $entity->getExerciseVariants());
        $this->assertInstanceOf(ExerciseVariant::class, $entity->getExerciseVariants()[0]);
        $this->assertEquals($entity->getExerciseVariants()[0]->getName(), $name);
    }
}
