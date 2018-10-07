<?php

namespace PullUpService\Handler;

use PullUpDomain\Entity\Section;
use PullUpDomain\Entity\Goal;

use PullUpDomain\Entity\User;
use PullUpDomain\Repository\GoalRepositoryInterface;
use PullUpDomain\Repository\SectionRepositoryInterface;

use PullUpService\Command\MoveGoalToSectionCommand;

class MoveGoalToSectionHandler
{
    protected $sectionRepository;
    protected $goalRepository;
    protected $user;

    private $cachePath;

    /**
     * MoveGoalToSectionHandler constructor.
     * @param SectionRepositoryInterface $sectionRepository
     * @param GoalRepositoryInterface $goalRepository
     * @param User $user
     * @param null $eventBus
     * @param null $cachePath
     */
    public function __construct(
        SectionRepositoryInterface $sectionRepository,
        GoalRepositoryInterface $goalRepository,
        User $user,
        $eventBus = null,
        $cachePath = null
    )
    {
        $this->sectionRepository = $sectionRepository;
        $this->goalRepository = $goalRepository;
        $this->user = $user;
        $this->cachePath = $cachePath;
    }

    public function handle(MoveGoalToSectionCommand $command)
    {
        if ($this->cachePath) {
            //file_put_contents($this->cachePath . '/first_form.json', json_encode($data));
        }

        $section = $this->sectionRepository->getByUserAndName($this->user, $command->sectionName);
        if (!$section) {
            $section = Section::create($command->sectionName, '', $this->user);
        }

        $goal = $this->goalRepository->getById($command->goalId);
        if (!$goal) {
            throw new \Exception("GOAL_NOT_FOUND");
        }

        $goal->moveToSection($section);
    }
}