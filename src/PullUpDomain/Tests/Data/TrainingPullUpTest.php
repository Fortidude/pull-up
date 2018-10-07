<?php

namespace PullUpDomain\Tests\Data;

use PHPUnit\Framework\TestCase;

use PullUpDomain\Data\TrainingPullUp;

class TrainingPullUpTest extends TestCase
{
    public function setUp()
    {
    }

    public function testGetNextIsNotAvailableYetCauseOfInterval()
    {
        $service = new TrainingPullUp();

        $interval = new \DateInterval("P1D");
        $createdAt = new \DateTime('NOW');
        $createdAt->sub(new \DateInterval("P1DT50M"));

        $currentResponse = $service->getNextIsNotAvailableYetCauseOfInterval($createdAt, $interval);
        $timeLeft = $currentResponse['texts'][1];

        $this->assertEquals("0 dni, 0 godzin, 50 minut", $timeLeft);
    }
}
