<?php

namespace PullUpBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\User\UserInterface;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;

use PullUpBundle\Repository\GoalRepository;
use PullUpBundle\CommandBus\SimpleBus;

use PullUpDomain\Entity\User;

use PullUpService\Command;

class GoalController
{
    /** @var GoalRepository */
    protected $repository;

    /** @var User */
    protected $user;

    /** @var SimpleBus */
    protected $commandBus;

    /**
     * GoalController constructor.
     * @param GoalRepository $repository
     * @param UserInterface $user
     * @param SimpleBus $commandBus
     */
    public function __construct(GoalRepository $repository, UserInterface $user, SimpleBus $commandBus)
    {
        $this->repository = $repository;
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
     * @Rest\View(serializerGroups={"goal_list", "exercise_item", "exercise_variant_item"})
     * @return array
     */
    public function plannerListAction()
    {
        return $this->repository->getPlannerByUser($this->user);
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
}