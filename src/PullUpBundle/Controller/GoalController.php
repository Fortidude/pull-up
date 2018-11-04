<?php

namespace PullUpBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use PullUpBundle\CommandBus\SimpleBus;
use PullUpBundle\Repository\GoalRepository;
use PullUpDomain\Entity\User;
use PullUpDomain\Repository\GoalSetRepositoryInterface;
use PullUpService\Command;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\User\UserInterface;

class GoalController
{
    /** @var GoalRepository */
    protected $repository;

    /** @var GoalSetRepositoryInterface */
    protected $goalSetRepository;

    /** @var User */
    protected $user;

    /** @var SimpleBus */
    protected $commandBus;

    /**
     * GoalController constructor.
     * @param GoalRepository $repository
     * @param GoalSetRepositoryInterface $goalSetRepository
     * @param UserInterface $user
     * @param SimpleBus $commandBus
     */
    public function __construct(GoalRepository $repository,
        GoalSetRepositoryInterface $goalSetRepository,
        UserInterface $user,
        SimpleBus $commandBus) {
        $this->repository = $repository;
        $this->goalSetRepository = $goalSetRepository;
        $this->user = $user;
        $this->commandBus = $commandBus;
    }

    /**
     * @ApiDoc(
     *  section="Goal",
     *  description="Goal list by user"
     * )
     *
     * @Rest\View(serializerGroups={"goal_list", "exercise_item", "exercise_variant_item"})
     * @return array
     */
    public function listAction()
    {
        return $this->repository->getListByUser($this->user);
    }

    /**
     * @ApiDoc(
     *  section="Goal",
     *  description="Goal planner list by user"
     * )
     *
     * @Rest\View(serializerGroups={"goal_list", "exercise_item", "exercise_variant_item", "goal_set_list"})
     * @return array
     */
    public function plannerListAction()
    {
        if ($this->user->isPlannerCustomMode()) {
            return $this->repository->getPlannerByUser($this->user);
        }

        return $this->repository->getCalendarPlannerByUser($this->user);
    }

    /**
     * @ParamConverter("command", converter="validation_converter")
     *
     * @param Command\CreateGoalCommand $command
     * @return array
     */
    public function createAction(Command\CreateGoalCommand $command)
    {
        $this->commandBus->handle($command);
        return ['status' => true];
    }

    /**
     * @param string $id
     * @param Command\UpdateGoalCommand $command
     * @return array
     */
    public function updateAction($id, Command\UpdateGoalCommand $command)
    {
        $command->id = $id;

        $this->commandBus->handle($command);
        return ['status' => true];
    }

    /**
     * @param string $id
     * @return array
     */
    public function disableAction($id)
    {
        $command = new Command\DisableGoalCommand();
        $command->id = $id;

        $this->commandBus->handle($command);
        return ['status' => true];
    }

    /**
     * @ParamConverter("command", converter="validation_converter")
     *
     * @param Command\CreateGoalSetCommand $command
     * @return array
     */
    public function createSetAction(Command\CreateGoalSetCommand $command)
    {
        $this->commandBus->handle($command);
        return ['status' => true];
    }

    /**
     * @ApiDoc(
     *  section="Goal",
     *  description="Last Created Goal Set"
     * )
     *
     * @Rest\View(serializerGroups={"goal_set_item", "goal_item", "exercise_item"})
     *
     * @return array|\PullUpDomain\Entity\GoalSet
     */
    public function getLastSetByUserAction()
    {
        $result = $this->goalSetRepository->getLastByUser($this->user);
        return $result ? $result : [];
    }

    /**
     * @ApiDoc(
     *  section="Goal",
     *  description="Move goal to section",
     *  requirements={
     *      {"name"="goalId", "dataType"="string", "description"="ID of goal"},
     *      {"name"="sectionId", "dataType"="string", "description"="ID of section"}
     *   }
     * )
     *
     * @ParamConverter("command", converter="validation_converter")
     *
     * @param $goalId
     * @param $command
     * @return array
     */
    public function moveToSectionAction($goalId, Command\MoveGoalToSectionCommand $command)
    {
        $command->goalId = $goalId;

        $this->commandBus->handle($command);
        return ['status' => true];
    }

    public function getStatisticsAction()
    {
        return $this->repository->getStatistics($this->user);
    }

    /**
     * @Rest\View(serializerGroups={"goal_set_item", "goal_set_history", "exercise_item", "exercise_variant_item", "circuit_item"})
     */
    public function getSetsHistoryByDatePeriodAction(string $fromDate, string $toDate)
    {
        return $this->goalSetRepository->getByUserAndDatePeriod($this->user, new \DateTime($fromDate), new \DateTime($toDate));
    }
}
