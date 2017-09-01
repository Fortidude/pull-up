<?php

namespace PullUpBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;

use PullUpBundle\CommandBus\SimpleBus;
use PullUpBundle\Service\Training\TrainingPullUpManager;

use PullUpDomain\Entity\User;
use PullUpDomain\Data\FirstFormData;

use PullUpService\Command\SubmitTrainingPullUpCommand;
use PullUpService\Command\CreateFirstFormCommand;


class TrainingPullUpController
{
    /** @var User $user */
    protected $user;

    /** @var TrainingPullUpManager */
    protected $trainingPullUpManager;

    /** @var SimpleBus */
    protected $commandBus;

    /**
     * TrainingController constructor.
     * @param User $user
     * @param TrainingPullUpManager $trainingPullUpManager
     * @param SimpleBus $commandBus
     */
    public function __construct(User $user, TrainingPullUpManager $trainingPullUpManager, SimpleBus $commandBus)
    {
        $this->user = $user;
        $this->trainingPullUpManager = $trainingPullUpManager;
        $this->commandBus = $commandBus;
    }

    /**
     * @return array
     */
    public function getFirstFormAction()
    {
        return FirstFormData::getFormData();
    }

    /**
     * @ParamConverter("command", converter="validation_converter")
     *
     * @param CreateFirstFormCommand $command
     * @throws \Exception
     * @return array
     */
    public function postFirstFormAction(CreateFirstFormCommand $command)
    {
        $this->commandBus->handle($command);
        return ['status' => true];
    }

    /**
     * @return array
     */
    public function getCurrentTrainingAction()
    {
        return $this->trainingPullUpManager->getCurrent($this->user);
    }

    /**
     * @ParamConverter("command", converter="validation_converter")
     *
     * @param SubmitTrainingPullUpCommand $command
     * @throws \Exception
     * @return array
     */
    public function postCurrentTrainingAction(SubmitTrainingPullUpCommand $command)
    {
        $this->commandBus->handle($command);
        return ['status' => true];
    }
}