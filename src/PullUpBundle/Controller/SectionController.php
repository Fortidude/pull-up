<?php

namespace PullUpBundle\Controller;

use PullUpDomain\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use FOS\RestBundle\Controller\Annotations as Rest;

use PullUpBundle\Repository\SectionRepository;
use PullUpBundle\CommandBus\SimpleBus;

use PullUpService\Command;

class SectionController
{
    /** @var SectionRepository */
    protected $repository;

    /** @var User */
    protected $user;

    /** @var SimpleBus */
    protected $commandBus;

    /**
     * SectionController constructor.
     * @param SectionRepository $repository
     * @param User $user
     * @param SimpleBus $commandBus
     */
    public function __construct(SectionRepository $repository, User $user, SimpleBus $commandBus)
    {
        $this->repository = $repository;
        $this->user = $user;
        $this->commandBus = $commandBus;
    }

    /**
     * @Rest\View(serializerGroups={"section_list", "section_list"})
     * @return array
     */
    public function listAction()
    {
        return $this->repository->getByUser($this->user);
    }

    /**
     *
     * @ParamConverter("command", converter="validation_converter")
     *
     * @param Command\CreateSectionCommand $command
     * @return array
     */
    public function createAction(Command\CreateSectionCommand $command)
    {
        $this->commandBus->handle($command);
        return ['status' => true];
    }
}