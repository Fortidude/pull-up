<?php

namespace PullUpBundle\Controller\Api;

use PullUpDomain\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\User\UserInterface;

use FOS\RestBundle\Controller\Annotations as Rest;

use PullUpBundle\Repository\ExerciseRepository;
use PullUpBundle\CommandBus\SimpleBus;

use PullUpService\Command;

class ExerciseController
{
    /** @var ExerciseRepository */
    protected $repository;

    /** @var User */
    protected $user;

    /** @var SimpleBus */
    protected $commandBus;

    /**
     * UserController constructor.
     * @param ExerciseRepository $repository
     * @param SimpleBus $commandBus
     */
    public function __construct(ExerciseRepository $repository, User $user, SimpleBus $commandBus)
    {
        $this->repository = $repository;
        $this->user = $user;
        $this->commandBus = $commandBus;
    }

    /**
     * @Rest\View(serializerGroups={"exercise_list", "exercise_variant_list"})
     * @return array
     */
    public function listAction()
    {
        return $this->repository->getListByUser($this->user);
    }

    /**
     *
     * @ParamConverter("command", converter="validation_converter")
     *
     * @param Command\CreateExerciseCommand $command
     * @return array
     */
    public function createAction(Command\CreateExerciseCommand $command)
    {
        $this->commandBus->handle($command);
        return ['status' => true];
    }

    /**
     * 
     * @ParamConverter("command", converter="validation_converter")
     * 
     * @param string $id
     * @param Command\UpdateGoalCommand $command
     * @return array
     */
    public function updateAction($id, Command\UpdateExerciseCommand $command)
    {
        $command->id = $id;
        $this->commandBus->handle($command);
        return ['status' => true];
    }
}