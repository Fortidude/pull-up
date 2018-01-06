<?php

namespace PullUpBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\User\UserInterface;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;

use PullUpDomain\Entity\User;
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
     * @param UserInterface $user
     * @param SimpleBus $commandBus
     */
    public function __construct(SectionRepository $repository, UserInterface $user, SimpleBus $commandBus)
    {
        $this->repository = $repository;
        $this->user = $user;
        $this->commandBus = $commandBus;
    }

    /**
     * @ApiDoc(
     *  section="Section",
     *  description="Sections of Goals"
     * )
     *
     * @Rest\View(serializerGroups={"section_list", "goal_list", "exercise_item", "exercise_variant_item"})
     * @return array
     */
    public function listAction()
    {
        return $this->repository->getByUser($this->user);
    }

    /**
     * @ApiDoc(
     *  section="Section",
     *  description="Create section",
     *  parameters={
     *     {"name"="name", "dataType"="string", "required"=true, "description"="name of section"},
     *     {"name"="description", "dataType"="string", "required"=true, "description"="description of section, null able"}
     * }
     * )
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