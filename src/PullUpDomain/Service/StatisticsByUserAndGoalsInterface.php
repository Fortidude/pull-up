<?php

namespace PullUpDomain\Service;

use PullUpDomain\Entity\Goal;
use PullUpDomain\Repository\Response\GoalStatisticsResponse;

interface StatisticsByUserAndGoalsInterface
{
    /**
     * @param Goal[] $currentCircleGoals
     * @param Goal[] $lastCircleGoals
     * @param Goal[] $allGoals
     * @return GoalStatisticsResponse
     */
    public function get(array $currentCircleGoals, array $lastCircleGoals, array $allGoals): GoalStatisticsResponse;
}
