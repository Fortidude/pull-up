<?php

namespace PullUpBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;

use PullUpBundle\CommandBus\SimpleBus;
use PullUpDomain\Entity\User;
use PullUpService\Command\UpdateSettingsCommand;

class SettingsController
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
    public function __construct(UserManagerInterface $repository, UserInterface $user, SimpleBus $commandBus)
    {
        $this->repository = $repository;
        $this->user = $user;
        $this->commandBus = $commandBus;
    }

    /**
     * @ParamConverter("command", converter="validation_converter")
     *
     * @param UpdateSettingsCommand $command
     * @return array
     */
    public function updateAction(UpdateSettingsCommand $command)
    {
        $this->commandBus->handle($command);
        return ['status' => true];
    }
}