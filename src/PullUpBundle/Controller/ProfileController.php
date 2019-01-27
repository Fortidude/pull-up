<?php

namespace PullUpBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\User\UserInterface;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\UserBundle\Model\UserManagerInterface;

use PullUpBundle\CommandBus\SimpleBus;
use PullUpService\Command;
use PullUpDomain\Entity\User;


class ProfileController
{
    /** @var UserManagerInterface */
    protected $repository;

    /** @var User */
    protected $user;

    /** @var SimpleBus */
    protected $commandBus;

    /**
     * ProfileController constructor.
     * @param UserManagerInterface $repository
     * @param UserInterface $user
     * @param SimpleBus $commandBus
     */
    public function __construct(UserManagerInterface $repository, UserInterface $user,  SimpleBus $commandBus)
    {
        $this->repository = $repository;
        $this->user = $user;
        $this->commandBus = $commandBus;
    }

    /**
     * @Rest\View(serializerGroups={"user_item", "profile", "circuit_item"})
     * @return User
     */
    public function currentAction()
    {
        return $this->user;
    }

    /**
     * @ParamConverter("command", converter="validation_converter")
     *
     * @param Command\UpdateUserDeviceIdCommand $command
     * @return array
     */
    public function changeUserDeviceIdAction(Command\UpdateUserDeviceIdCommand $command)
    {
        $this->commandBus->handle($command);
        return ['status' => true];
    }
}