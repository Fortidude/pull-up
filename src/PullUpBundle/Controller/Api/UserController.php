<?php

namespace PullUpBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\UserBundle\Model\UserManagerInterface;
use PullUpBundle\CommandBus\SimpleBus;
use PullUpService\Command;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

    /**
     * @ParamConverter("command", converter="validation_converter")
     * @Rest\View(serializerGroups={})
     *
     * @param Command\PasswordRemindCommand $command
     * @return array
     */
    public function passwordRemindAction(Command\PasswordRemindCommand $command)
    {
        $this->commandBus->handle($command);
        return ['status' => true];
    }

    public function passwordRemindKeyValidateAction(string $email, string $key)
    {
        $command = new Command\PasswordRemindKeyValidateCommand();
        $command->email = $email;
        $command->key = $key;

        $this->commandBus->handle($command);
        return new RedirectResponse("pullup://passwordreset/{$key}");
    }

    /**
     * @ParamConverter("command", converter="validation_converter")
     * @Rest\View(serializerGroups={})
     *
     * @param Command\PasswordChangeCommand $command
     * @return array
     */
    public function passwordChangeAction(Command\PasswordChangeCommand $command)
    {
        $this->commandBus->handle($command);
        return ['status' => true];
    }

    public function checkTokenAction()
    {
        return [];
    }

    /**
     * @Rest\View(serializerGroups={})
     *
     * @return array
     */
    public function deleteAction()
    {
        $command = new Command\RemoveUserCommand();
        $this->commandBus->handle($command);
        
        return [];
    }
}
