<?php

namespace PullUpBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\User\UserInterface;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\UserBundle\Model\UserManagerInterface;

use PullUpBundle\CommandBus\SimpleBus;

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
     * @Rest\View(serializerGroups={"user_item", "profile"})
     * @return User
     */
    public function currentAction()
    {
        return $this->user;
    }
}