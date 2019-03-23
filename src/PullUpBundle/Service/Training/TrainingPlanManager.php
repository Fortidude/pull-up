<?php

namespace PullUpBundle\Service\Training;

use PullUpBundle\Exception\BadRequest;
use PullUpDomain\Entity\Goal;
use PullUpDomain\Entity\Section;
use PullUpDomain\Entity\User;
use PullUpDomain\Repository\ExerciseRepositoryInterface;
use PullUpDomain\Repository\GoalRepositoryInterface;
use PullUpDomain\Repository\SectionRepositoryInterface;
use PullUpDomain\Repository\UserRepositoryInterface;
use PullUpDomain\Service\TrainingPlanManagerInterface;

class TrainingPlanManager implements TrainingPlanManagerInterface
{
    /** @var UserRepositoryInterface */
    protected $userRepository;

    /** @var GoalRepositoryInterface */
    protected $goalRepository;

    /** @var SectionRepositoryInterface */
    protected $sectionRepository;

    /** @var ExerciseRepositoryInterface */
    protected $exerciseRepository;

    const BASIC_CONFIG = [
        [
            "name" => "Poniedziałek",
            "description" => "",
            "goals" => [
                [
                    // Squats
                    "exercise_id" => "a56401d8-1701-46b5-9cc4-cf3584c6b4aa",
                    "variant_id" => "c51781f7-b9d6-4fd7-b353-a318f8dbb3b7",
                    "sets" => 3,
                ],
                [
                    // Barbell Bench Press / medium grip
                    "exercise_id" => "5154325f-584b-405d-9204-f9a7ef8a8bb7",
                    "variant_id" => "61e86f65-1811-4424-b1f9-c432e28b6085",
                    "sets" => 2,
                ],
                [
                    // Dumbbell Flyes
                    "exercise_id" => "c9d7af5a-cd07-4a33-a43d-34c267e2e9c1",
                    "variant_id" => null,
                    "sets" => 2,
                ],
                [
                    // Deadlift
                    "exercise_id" => "d50069af-fa08-40b0-9f59-c8bdb7df355b",
                    "variant_id" => "",
                    "sets" => 3,
                ],
                [
                    // Lat Pulldown / Wide-Grip
                    "exercise_id" => "a9208542-0db3-4127-8c65-c0453074a2b1",
                    "variant_id" => "0a5eca6a-3ab6-4078-9c89-83005595d5ee",
                    "sets" => 3,
                ],
                [
                    // Seated Cable Rows
                    "exercise_id" => "c1eca564-dba7-4e73-9094-aac2b9e8431d",
                    "variant_id" => null,
                    "sets" => 2,
                ],
                [
                    // Dumbbell Shoulder Press
                    "exercise_id" => "216b04df-e923-4aa8-95f5-09f2a411adb1",
                    "variant_id" => "b2cd5ca0-3a08-4ba0-b431-99c6373cdb4f",
                    "sets" => 2,
                ],
                [
                    // Biceps curl / barbell
                    "exercise_id" => "03e5c879-79cb-4930-bfdb-de0b45675099",
                    "variant_id" => "5f2433ab-a64d-48fb-b2dd-4c40120c6e12",
                    "sets" => 3,
                ],
                [
                    // Triceps Pushdown
                    "exercise_id" => "5efc7358-6af7-4bc0-aa6c-d93c1c656762",
                    "variant_id" => "f2248c44-f67a-49a2-8e31-6f0d1cbb7d11",
                    "sets" => 3,
                ],
                [
                    // Standing Calf Raises
                    "exercise_id" => "bac9fd1f-8568-4353-9935-d120cf365f90",
                    "variant_id" => null,
                    "sets" => 3,
                ],
                [
                    // Crunches
                    "exercise_id" => "294c9c73-0478-46c3-a01f-19b6abf54f0d",
                    "variant_id" => null,
                    "sets" => 3,
                ],
            ],
        ],
        [
            "name" => "Środa",
            "description" => "",
            "goals" => [
                [
                    // Squats
                    "exercise_id" => "8cf01b8c-effc-4942-8b96-7ee8f706a2bc",
                    "variant_id" => null,
                    "sets" => 3,
                ],
                [
                    // Barbell Bench Press / medium grip
                    "exercise_id" => "5154325f-584b-405d-9204-f9a7ef8a8bb7",
                    "variant_id" => "61e86f65-1811-4424-b1f9-c432e28b6085",
                    "sets" => 2,
                ],
                [
                    // Dumbbell Flyes
                    "exercise_id" => "c9d7af5a-cd07-4a33-a43d-34c267e2e9c1",
                    "variant_id" => null,
                    "sets" => 2,
                ],
                [
                    // Deadlift
                    "exercise_id" => "d50069af-fa08-40b0-9f59-c8bdb7df355b",
                    "variant_id" => "",
                    "sets" => 3,
                ],
                [
                    // Pull up
                    "exercise_id" => "3fe8dfa8-f3aa-4425-aec1-f793a210dbd1",
                    "variant_id" => null,
                    "sets" => 3,
                ],
                [
                    // Seated Cable Rows
                    "exercise_id" => "c1eca564-dba7-4e73-9094-aac2b9e8431d",
                    "variant_id" => null,
                    "sets" => 2,
                ],
                [
                    // Dumbbell Shoulder Press
                    "exercise_id" => "216b04df-e923-4aa8-95f5-09f2a411adb1",
                    "variant_id" => "4c3ef8f6-2c39-4570-a336-eb717bbc96d7",
                    "sets" => 2,
                ],
                [
                    // Biceps curl / barbell
                    "exercise_id" => "03e5c879-79cb-4930-bfdb-de0b45675099",
                    "variant_id" => "5f2433ab-a64d-48fb-b2dd-4c40120c6e12",
                    "sets" => 3,
                ],
                [
                    // Triceps Pushdown
                    "exercise_id" => "5efc7358-6af7-4bc0-aa6c-d93c1c656762",
                    "variant_id" => "f2248c44-f67a-49a2-8e31-6f0d1cbb7d11",
                    "sets" => 3,
                ],
                [
                    // Standing Calf Raises
                    "exercise_id" => "bac9fd1f-8568-4353-9935-d120cf365f90",
                    "variant_id" => null,
                    "sets" => 3,
                ],
                [
                    // Crunches
                    "exercise_id" => "294c9c73-0478-46c3-a01f-19b6abf54f0d",
                    "variant_id" => null,
                    "sets" => 3,
                ],
            ],
        ],
        [
            "name" => "Piątek",
            "description" => "",
            "goals" => [
                [
                    // Squats
                    "exercise_id" => "a56401d8-1701-46b5-9cc4-cf3584c6b4aa",
                    "variant_id" => "c51781f7-b9d6-4fd7-b353-a318f8dbb3b7",
                    "sets" => 3,
                ],
                [
                    // Barbell Bench Press / medium grip
                    "exercise_id" => "5154325f-584b-405d-9204-f9a7ef8a8bb7",
                    "variant_id" => "61e86f65-1811-4424-b1f9-c432e28b6085",
                    "sets" => 2,
                ],
                [
                    // Dumbbell Flyes
                    "exercise_id" => "c9d7af5a-cd07-4a33-a43d-34c267e2e9c1",
                    "variant_id" => null,
                    "sets" => 2,
                ],
                [
                    // Deadlift
                    "exercise_id" => "d50069af-fa08-40b0-9f59-c8bdb7df355b",
                    "variant_id" => "",
                    "sets" => 3,
                ],
                [
                    // Pullup
                    "exercise_id" => "3fe8dfa8-f3aa-4425-aec1-f793a210dbd1",
                    "variant_id" => null,
                    "sets" => 3,
                ],
                [
                    // Seated Cable Rows
                    "exercise_id" => "c1eca564-dba7-4e73-9094-aac2b9e8431d",
                    "variant_id" => null,
                    "sets" => 2,
                ],
                [
                    // Dumbbell Shoulder Press
                    "exercise_id" => "216b04df-e923-4aa8-95f5-09f2a411adb1",
                    "variant_id" => "b2cd5ca0-3a08-4ba0-b431-99c6373cdb4f",
                    "sets" => 2,
                ],
                [
                    // Biceps curl / barbell
                    "exercise_id" => "03e5c879-79cb-4930-bfdb-de0b45675099",
                    "variant_id" => "5f2433ab-a64d-48fb-b2dd-4c40120c6e12",
                    "sets" => 3,
                ],
                [
                    // Triceps Pushdown
                    "exercise_id" => "5efc7358-6af7-4bc0-aa6c-d93c1c656762",
                    "variant_id" => "f2248c44-f67a-49a2-8e31-6f0d1cbb7d11",
                    "sets" => 3,
                ],
                [
                    // Standing Calf Raises
                    "exercise_id" => "bac9fd1f-8568-4353-9935-d120cf365f90",
                    "variant_id" => null,
                    "sets" => 3,
                ],
                [
                    // Crunches
                    "exercise_id" => "294c9c73-0478-46c3-a01f-19b6abf54f0d",
                    "variant_id" => null,
                    "sets" => 3,
                ],
            ],
        ],
    ];

    const BASIC_PUSH_PULL_CONFIG = [
        [
            "name" => "Push",
            "description" => "",
            "goals" => [
                [
                    // Barbell Bench Press / medium grip
                    "exercise_id" => "5154325f-584b-405d-9204-f9a7ef8a8bb7",
                    "variant_id" => "61e86f65-1811-4424-b1f9-c432e28b6085",
                    "sets" => 3,
                ],
                [
                    // push ups, traditional
                    "exercise_id" => "273a7542-0b16-4771-9924-1dc53ddc6a47",
                    "variant_id" => "9b5581fa-f48d-45fa-a821-28c711aa2075",
                    "sets" => 3,
                ],
                [
                    // barbell Shoulder Press
                    "exercise_id" => "216b04df-e923-4aa8-95f5-09f2a411adb1",
                    "variant_id" => "4c3ef8f6-2c39-4570-a336-eb717bbc96d7",
                    "sets" => 3,
                ],
                [
                    // Dips
                    "exercise_id" => "ce402b0b-6145-41fd-9d35-6d433a93fc5e",
                    "variant_id" => null,
                    "sets" => 3,
                ],
                [
                    // Triceps Pushdown
                    "exercise_id" => "5efc7358-6af7-4bc0-aa6c-d93c1c656762",
                    "variant_id" => "f2248c44-f67a-49a2-8e31-6f0d1cbb7d11",
                    "sets" => 3,
                ]
            ]
        ],
        [
            "name" => "Pull",
            "description" => "",
            "goals" => [
                [
                    // Pull up / Traditional
                    "exercise_id" => "3fe8dfa8-f3aa-4425-aec1-f793a210dbd1",
                    "variant_id" => "de69f477-9f60-417c-8b5b-8928cd629a6e",
                    "sets" => 3,
                ],
                [
                    // Pull up / Australian
                    "exercise_id" => "3fe8dfa8-f3aa-4425-aec1-f793a210dbd1",
                    "variant_id" => "5bf10419-8f50-482e-b908-d2af29d6c544",
                    "sets" => 4,
                ],
                [
                    // Lat Pulldown / Wide-Grip
                    "exercise_id" => "a9208542-0db3-4127-8c65-c0453074a2b1",
                    "variant_id" => "0a5eca6a-3ab6-4078-9c89-83005595d5ee",
                    "sets" => 4,
                ],
                [
                    // Biceps curl / barbell
                    "exercise_id" => "03e5c879-79cb-4930-bfdb-de0b45675099",
                    "variant_id" => "5f2433ab-a64d-48fb-b2dd-4c40120c6e12",
                    "sets" => 3,
                ],
            ]
        ],
        [
            "name" => "Legs",
            "description" => "",
            "goals" => [
                [
                    // Squats
                    "exercise_id" => "8cf01b8c-effc-4942-8b96-7ee8f706a2bc",
                    "variant_id" => null,
                    "sets" => 3,
                ],
                [
                    // Lunge
                    "exercise_id" => "8cf01b8c-effc-4942-8b96-7ee8f706a2bc",
                    "variant_id" => null,
                    "sets" => 3,
                ],
                [
                    // Deadlift
                    "exercise_id" => "d50069af-fa08-40b0-9f59-c8bdb7df355b",
                    "variant_id" => "",
                    "sets" => 4,
                ],
                [
                    // Standing Calf Raises
                    "exercise_id" => "bac9fd1f-8568-4353-9935-d120cf365f90",
                    "variant_id" => null,
                    "sets" => 3,
                ],
            ]
        ]
    ];

    const INTERMEDIATE_CONFIG = [
        [
            "name" => "Poniedziałek",
            "description" => "",
            "goals" => [
            ],
        ],
        [
            "name" => "Środa",
            "description" => "",
            "goals" => [
            ],
        ],
    ];

    public function __construct(
        UserRepositoryInterface $userRepository,
        GoalRepositoryInterface $goalRepository,
        SectionRepositoryInterface $sectionRepository,
        ExerciseRepositoryInterface $exerciseRepository
    ) {
        $this->userRepository = $userRepository;
        $this->goalRepository = $goalRepository;
        $this->sectionRepository = $sectionRepository;
        $this->exerciseRepository = $exerciseRepository;
    }

    public function assignUserToPlan(String $plan, User $user): void
    {
        $goalExistings = $this->goalRepository->getListByUser($user);
        if (!empty($goalExistings)) {
            throw new BadRequest("User have a goals");
        }

        switch ($plan) {
            case "basic":
                $this->assignBasicPlan($user);
                break;

            case "basic-push-pull":
                $this->assignBasicPushPullPlan($user);
                break;

            case "intermediate":
                $this->assignIntermediatePlan($user);
                break;

            default:
                throw new BadRequest("Invalid plan name");
        }
    }

    protected function assignBasicPlan(User $user): void
    {
        $this->assign(self::BASIC_CONFIG, $user);
    }

    protected function assignBasicPushPullPlan(User $user): void
    {
        $this->assign(self::BASIC_PUSH_PULL_CONFIG, $user);
    }

    protected function assignIntermediatePlan(User $user): void
    {
        $this->assign(self::INTERMEDIATE_CONFIG, $user);
    }

    protected function assign(array $config, User $user)
    {
        foreach ($config as $key => $section) {
            $sectionName = $section['name'];
            $sectionDescription = $section['description'];

            $goals = [];

            foreach ($section['goals'] as $goalData) {
                $exercise = $this->exerciseRepository->getByNameOrId($goalData['exercise_id']);
                $variant = null;
                if ($goalData['variant_id']) {
                    foreach ($exercise->getExerciseVariants() as $exerciseVariant) {
                        if ($exerciseVariant->getId() === $goalData['variant_id']) {
                            $variant = $exerciseVariant;
                        }
                    }
                }

                $goals[] = Goal::create(
                    $exercise->getName(),
                    "",
                    $user,
                    $exercise,
                    $variant,
                    array_key_exists('sets', $goalData) ? $goalData['sets'] : null,
                    array_key_exists('reps', $goalData) ? $goalData['reps'] : null,
                    array_key_exists('weight', $goalData) ? $goalData['weight'] : null,
                    array_key_exists('time', $goalData) ? $goalData['time'] : null
                );
            }

            $sectionEntity = Section::create($sectionName, $sectionDescription, $user, $goals);
            $this->sectionRepository->add($sectionEntity);
        }
    }
}
