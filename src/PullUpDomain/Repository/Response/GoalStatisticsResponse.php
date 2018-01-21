<?php

namespace PullUpDomain\Repository\Response;

use PullUpDomain\Entity\Exercise;
use PullUpDomain\Entity\Goal;

class GoalStatisticsResponse
{
    /** @var Exercise[] */
    public $topFiveExercises = [];

    /** @var array */
    public $percentageExercisesUsage = [];

    /** @var array */
    public $percentageGoalsAchieved = [];
    public $percentGoalsAchieved = 0;

    /** @var Goal[] */
    public $goalsNeverAchieved = [];

    /** @var Goal[] */
    public $lastCircleGoalsAchieved = [];

    /** @var Goal[] */
    public $lastCircleGoalsNotAchieved = [];

    /** @var Goal[] */
    public $currentCircleGoalsAchieved = [];

    /** @var Goal[] */
    public $currentCircleGoalsNotAchieved = [];
}