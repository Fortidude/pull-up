<?php

namespace PullUpBundle\Service\Statistics;

use PullUpDomain\Entity\Circuit;
use PullUpDomain\Entity\Goal;
use PullUpDomain\Entity\User;
use PullUpDomain\Repository\Response\GoalStatisticsResponse;
use PullUpDomain\Service\StatisticsByUserAndGoalsInterface;

class ByUserAndGoals// implements StatisticsByUserAndGoalsInterface
{
    /** @var Goal[] */
    private $allGoals;

    /**
     * @param User $user
     * @param Goal[] $allGoals
     * @return GoalStatisticsResponse
     */
    public function get(User $user, array $allGoals): GoalStatisticsResponse
    {
        $this->allGoals = $allGoals;

        $currentCircuit = $user->getCurrentTrainingCircuit();
        $lastCircuit = $user->getTrainingCircuitByDate($currentCircuit->getStartAt()->sub(new \DateInterval("P1D")));

        $currentCirclePercentageGoalsAchieved = $this->percentageAchievedGoals($allGoals, [$currentCircuit]);
        $lastCirclePercentageGoalsAchieved = $this->percentageAchievedGoals($allGoals, [$lastCircuit]);
        $percentageGoalsAchieved = $this->percentageAchievedGoals($allGoals, $user->getCircuits()->getValues());

        $response = new GoalStatisticsResponse();
        $response->percentageExercisesUsage = $this->percentageExerciseUsage($allGoals);
        $response->percentageGoalsAchieved = $percentageGoalsAchieved;
        $response->percentGoalsAchieved = $this->percentGoalsAchieved($percentageGoalsAchieved);

        $response->currentCirclePercentageGoalsAchieved = $currentCirclePercentageGoalsAchieved;
        $response->currentCirclePercentGoalsAchieved = $this->percentGoalsAchieved($currentCirclePercentageGoalsAchieved);

        $response->lastCirclePercentageGoalsAchieved = $lastCirclePercentageGoalsAchieved;
        $response->lastCirclePercentGoalsAchieved = $this->percentGoalsAchieved($lastCirclePercentageGoalsAchieved);

        return $response;
    }

    private function percentGoalsAchieved(array $percentageAchievedGoalsResult)
    {
        $totalPercent = 0;
        $total = 0;
        foreach ($percentageAchievedGoalsResult['goals'] as $goalResult) {
            $total++;

            if (array_key_exists('percentage', $goalResult) && $goalResult['percentage']/* && $goalResult['percentage'] >= 100*/) {
                $totalPercent += $goalResult['percentage'];
            }
        }

        if ($total === 0) {
            return 0;
        }

        return (int)($totalPercent / $total);
    }

    /**
     * @param Goal[] $goals
     * @param Circuit[] $circuits
     * @return array
     */
    private function percentageAchievedGoals(array $goals, array $circuits = [])
    {
        $results = [
            'total_goals' => 0,
            'total_circuits' => 0,
            'goals' => [],
        ];

        $uniqueCircuits = [];
        $totalGoals = 0;
        $goalsParsed = [];

        foreach ($circuits as $circuit) {
            $uniqueCircuits[$circuit->getId()] = $circuit->getId();
        }

        foreach ($goals as $goal) {
            $requiredAmount = $goal->getRequiredAmount();

            if ($requiredAmount === 0) {
                continue;
            }

            $totalCircuits = 0;
            $achievedAmount = 0;
            $goalPercentage = 0;
            $byCircuits = [];

            foreach ($goal->getSets() as $set) {
                $circuitId = $set->getCircuit()->getId();
                if (!array_key_exists($circuitId, $uniqueCircuits)) {
                    continue;
                }

                if (array_key_exists($circuitId, $byCircuits)) {
                    $byCircuits[$circuitId] += $set->getValue();
                } else {
                    $byCircuits[$circuitId] = $set->getValue();
                }
            }
            foreach ($byCircuits as $key => $byCircuit) {
                $percentage = (int)($byCircuit / $requiredAmount * 100);
                if ($percentage >= 100) {
                    $achievedAmount++;
                }

                $goalPercentage += $percentage > 100 ? 100 : $percentage;
            }

            foreach ($circuits as $circuit) {
                if ($circuit->getEndAt() >= $goal->getCreateAt()) {
                    $totalCircuits++;
                }
            }

            $totalGoals++;
            $goalsParsed[$goal->getId()] = $goal->getId();

            $results['goals'][] = [
                'name' => $goal->getExerciseName(),
                'variant_name' => $goal->getExerciseVariantName(),
                'percentage' => $totalCircuits > 0 ? (int)($goalPercentage / $totalCircuits) : 0,
                'achieved_amount' => $achievedAmount
            ];
        }

        $results['total_circuits'] = count($uniqueCircuits);
        $results['total_goals'] = count($this->allGoals);

        if ($totalGoals !== count($this->allGoals)) {
            foreach ($this->allGoals as $goal) {
                if (!array_key_exists($goal->getId(), $goalsParsed)) {
                    $results['goals'][] = [
                        'name' => $goal->getExerciseName(),
                        'variant_name' => $goal->getExerciseVariantName(),
                        'percentage' => 0,
                        'achieved_amount' => 0
                    ];
                }
            }
        }

        return $results;
    }

    /**
     * @param Goal[] $goals
     * @return array
     */
    private function percentageExerciseUsage(array $goals)
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
