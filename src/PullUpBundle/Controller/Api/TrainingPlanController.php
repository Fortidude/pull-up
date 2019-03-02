<?php

namespace PullUpBundle\Controller\Api;

use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;

use PullUpBundle\CommandBus\SimpleBus;

use PullUpService\Command\AssignUserToPlanCommand;

use PullUpDomain\Entity\User;

class TrainingPlanController
{
    /** @var User $user */
    protected $user;

    /** @var SimpleBus */
    protected $commandBus;

    /**
     * TrainingPullUpController constructor.
     * @param UserInterface $user
     * @param SimpleBus $commandBus
     */
    public function __construct(UserInterface $user, SimpleBus $commandBus)
    {
        $this->user = $user;
        $this->commandBus = $commandBus;
    }

    /**
     * @ParamConverter("command", converter="validation_converter")
     *
     * @param CreateTrainingPullUpFirstFormCommand $command
     * @throws \Exception
     * @return array
     */
    public function assignUserToTrainingPlanAction(AssignUserToPlanCommand $command, String $plan)
    {
        $command->plan = $plan;
        $this->commandBus->handle($command);
        return ['status' => true];
    }
    
}