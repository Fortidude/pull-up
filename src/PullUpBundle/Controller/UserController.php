<?php

namespace PullUpBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\UserBundle\Model\UserManagerInterface;

use PullUpBundle\CommandBus\SimpleBus;

use PullUpService\Command;

class UserController
{
    /** @var UserManagerInterface */
    protected $repository;

    /** @var SimpleBus */
    protected $commandBus;

    /**
     * UserController constructor.
     * @param UserManagerInterface $repository
     * @param SimpleBus $commandBus
     */
    public function __construct(UserManagerInterface $repository, SimpleBus $commandBus)
    {
        $this->repository = $repository;
        $this->commandBus = $commandBus;
    }

    /**
     * @Rest\View(serializerGroups={})
     * @return array
     */
    public function listAction()
    {
        return ['dd'];
    }

    /**
     * @ParamConverter("command", converter="validation_converter")
     * @Rest\View(serializerGroups={})
     *
     * @param Command\LoginByFacebookCommand $command
     * @return array
     */
    public function loginByFacebookAction(Command\LoginByFacebookCommand $command)
    {
        $this->commandBus->handle($command);

        return ['status' => true];
    }

    /**
     * @ParamConverter("command", converter="validation_converter")
     * @Rest\View(serializerGroups={})
     *
     * @param Command\RegisterCommand $command
     * @return array
     */
    public function registerAction(Command\RegisterCommand $command)
    {
        $this->commandBus->handle($command);

        return ['status' => true];
    }

    public function checkTokenAction()
    {
        return [];
    }
}
