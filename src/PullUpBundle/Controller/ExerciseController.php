<?php

namespace PullUpBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use FOS\RestBundle\Controller\Annotations as Rest;

use PullUpBundle\Repository\ExerciseRepository;
use PullUpBundle\CommandBus\SimpleBus;

use PullUpService\Command;

class ExerciseController
{
    /** @var ExerciseRepository */
    protected $repository;

    /** @var SimpleBus */
    protected $commandBus;

    /**
     * UserController constructor.
     * @param ExerciseRepository $repository
     * @param SimpleBus $commandBus
     */
    public function __construct(ExerciseRepository $repository, SimpleBus $commandBus)
    {
        $this->repository = $repository;
        $this->commandBus = $commandBus;
    }

    /**
     * @Rest\View(serializerGroups={"exercise_list", "exercise_variant_list"})
     * @return array
     */
    public function listAction()
    {
        return $this->repository->getList();
    }
}