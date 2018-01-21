<?php

namespace PullUpBundle\Service\Statistics;

use PullUpDomain\Entity\Circuit;
use PullUpDomain\Entity\Goal;
use PullUpDomain\Repository\Response\GoalStatisticsResponse;

class ByUserAndGoals
{
    /**
     * @param Goal[] $goals
     * @return GoalStatisticsResponse
     */
    public function get(array $goals): GoalStatisticsResponse
    {
        $percentageGoalsAchieved = $this->percentageAchievedGoals($goals);

        $response = new GoalStatisticsResponse();
        $response->percentageExercisesUsage = $this->percentageExerciseUsage($goals);
        $response->percentageGoalsAchieved = $percentageGoalsAchieved;
        $response->percentGoalsAchieved = $this->percentGoalsAchieved($percentageGoalsAchieved);

        return $response;
    }

    public function percentGoalsAchieved(array $percentageAchievedGoalsResult)
    {
        $achieved = 0;
        $total = 0;
        foreach ($percentageAchievedGoalsResult['goals'] as $goalResult) {
            $total++;

            if (array_key_exists('percentage', $goalResult) && $goalResult['percentage'] && $goalResult['percentage'] >= 100) {
                $achieved++;
            }
        }

        return (int) ($achieved / $total * 100);
    }

    /**
     * @param Goal[] $goals
     * @return array
     */
    public function percentageAchievedGoals(array $goals)
    {
        $results = [
            'total_goals' => count($goals),
            'total_circuits' => 0,
            'goals' => [],
        ];

        $uniqueCircuits = [];

        foreach ($goals as $goal) {
            $requiredAmount = $goal->getRequiredAmount();

            if ($requiredAmount === 0) {
                continue;
            }

            $achievedAmount = 0;
            $byCircuits = [];

            foreach ($goal->getSets() as $set) {
                $uniqueCircuits[$set->getCircuit()->getId()] = $set->getCircuit()->getId();

                if (array_key_exists($set->getCircuit()->getId(), $byCircuits)) {
                    $byCircuits[$set->getCircuit()->getId()] += $set->getValue();
                } else {
                    $byCircuits[$set->getCircuit()->getId()] = $set->getValue();
                }
            }

            foreach ($byCircuits as $key => $byCircuit) {
                $percentage = (int)($byCircuit / $requiredAmount * 100);
                if ($percentage >= 100) {
                    $achievedAmount++;
                }
            }

            $total = count($byCircuits);
            $results['goals'][] = [
                'name' => $goal->getExerciseName(),
                'variant_name' => $goal->getExerciseVariantName(),
                'percentage' => $total > 0 ? (int)($achievedAmount / $total * 100) : 0
            ];
        }

        $results['total_circuits'] = count($uniqueCircuits);

        return $results;
    }

    /**
     * @param Goal[] $goals
     * @return array
     */
    public function percentageExerciseUsage(array $goals)
    {
        $exerciseUsage = [];
        $total = count($goals);

        foreach ($goals as $goal) {
            $name = $goal->getExercise()->getName();
            if (array_key_exists($name, $exerciseUsage)) {
                $exerciseUsage[$name]['amount']++;
            } else {
                $exerciseUsage[$name] = [
                    'name' => $name,
                    'amount' => 1,
                    'percentage' => 0
                ];
            }
        }

        foreach ($exerciseUsage as $key => $data) {
            $exerciseUsage[$key]['percentage'] = (int)($exerciseUsage[$key]['amount'] / $total * 100);
        }

        usort($exerciseUsage, function ($first, $second) {
            return $second['percentage'] - $first['percentage'];
        });

        return [
            'usage' => $exerciseUsage,
            'total' => $total
        ];
    }
}