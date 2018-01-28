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

        $currentCirclePercentageGoalsAchieved = $this->percentageAchievedGoals($allGoals, $currentCircuit);
        $lastCirclePercentageGoalsAchieved = $this->percentageAchievedGoals($allGoals, $lastCircuit);
        $percentageGoalsAchieved = $this->percentageAchievedGoals($allGoals);

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
        $achieved = 0;
        $total = 0;
        foreach ($percentageAchievedGoalsResult['goals'] as $goalResult) {
            $total++;

            if (array_key_exists('percentage', $goalResult) && $goalResult['percentage'] && $goalResult['percentage'] >= 100) {
                $achieved++;
            }
        }

        if ($total === 0) {
            return 0;
        }

        return (int)($achieved / $total * 100);
    }

    /**
     * @param array $goals
     * @param Circuit|null $circuit
     * @return array
     */
    private function percentageAchievedGoals(array $goals, Circuit $circuit = null)
    {
        $results = [
            'total_goals' => 0,
            'total_circuits' => 0,
            'goals' => [],
        ];

        $uniqueCircuits = [];
        $totalGoals = 0;
        $goalsParsed = [];

        foreach ($goals as $goal) {
            $requiredAmount = $goal->getRequiredAmount();

            if ($requiredAmount === 0) {
                continue;
            }

            $achievedAmount = 0;
            $byCircuits = [];

            foreach ($goal->getSets() as $set) {
                $circuitId = $set->getCircuit()->getId();
                if ($circuit && $circuit->getId() !== $circuitId) {
                    continue;
                }

                $uniqueCircuits[$circuitId] = $circuitId;

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
            }

            $totalGoals++;
            $total = count($byCircuits);
            $goalsParsed[$goal->getId()] = $goal->getId();

            if (count($byCircuits) === 1) {
                $byCircuit = array_shift($byCircuits);
                $goalPercentage = (int)($byCircuit / $requiredAmount * 100);
            } else {
                $goalPercentage = $total > 0 ? (int)($achievedAmount / $total * 100) : 0;
            }

            $results['goals'][] = [
                'name' => $goal->getExerciseName(),
                'variant_name' => $goal->getExerciseVariantName(),
                'percentage' => $goalPercentage
            ];
        }

        $results['total_circuits'] = count($uniqueCircuits);
        $results['total_goals'] = count($this->allGoals);

        if ($circuit && $totalGoals !== count($this->allGoals)) {
            foreach ($this->allGoals as $goal) {
                if (!array_key_exists($goal->getId(), $goalsParsed)) {
                    $results['goals'][] = [
                        'name' => $goal->getExerciseName(),
                        'variant_name' => $goal->getExerciseVariantName(),
                        'percentage' => 0
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