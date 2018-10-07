<?php

namespace PullUpDomain\Repository\Response;

use PullUpDomain\Entity\Exercise;
use PullUpDomain\Entity\Goal;

class GoalStatisticsResponse
{
    /** @var array */
    public $percentageExercisesUsage = [];
    public $percentageSetsUsage = [];

    /** @var array */
    public $percentageGoalsAchieved = [];
    public $percentGoalsAchieved = 0;

    /** @var array */
    public $lastCirclePercentageGoalsAchieved = [];
    public $lastCirclePercentGoalsAchieved = 0;

    /** @var array */
    public $currentCirclePercentageGoalsAchieved = [];
    public $currentCirclePercentGoalsAchieved = 0;

    /** @var Goal[] */
    public $goalsNeverAchieved = [];

    /** @var array */
    public $achievedPerCircuit = [];
}
