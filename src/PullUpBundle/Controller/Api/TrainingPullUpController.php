<?php

namespace PullUpBundle\Controller\Api;

use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;

use PullUpBundle\CommandBus\SimpleBus;
use PullUpBundle\Service\Training\TrainingPullUpManager;
use PullUpBundle\Service\Training\TrainingPullUpHistory;

use PullUpDomain\Entity\User;
use PullUpDomain\Data\FirstFormData;

use PullUpService\Command\SubmitTrainingPullUpCommand;
use PullUpService\Command\CreateTrainingPullUpFirstFormCommand;


class TrainingPullUpController
{
    /** @var User $user */
    protected $user;

    /** @var TrainingPullUpManager */
    protected $trainingPullUpManager;

    /** @var TrainingPullUpHistory */
    protected $trainingPullUpHistory;

    /** @var SimpleBus */
    protected $commandBus;

    /**
     * TrainingPullUpController constructor.
     * @param UserInterface $user
     * @param TrainingPullUpManager $trainingPullUpManager
     * @param TrainingPullUpHistory $trainingPullUpHistory
     * @param SimpleBus $commandBus
     */
    public function __construct(UserInterface $user, TrainingPullUpManager $trainingPullUpManager, TrainingPullUpHistory $trainingPullUpHistory, SimpleBus $commandBus)
    {
        $this->user = $user;
        $this->trainingPullUpManager = $trainingPullUpManager;
        $this->trainingPullUpHistory = $trainingPullUpHistory;
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
     * @param CreateTrainingPullUpFirstFormCommand $command
     * @throws \Exception
     * @return array
     */
    public function postFirstFormAction(CreateTrainingPullUpFirstFormCommand $command)
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

    /**
     * @Rest\View(serializerGroups={"user_item", "profile", "pullup_list"})
     *
     * @return array
     */
    public function getHistoryAction()
    {
        return $this->trainingPullUpHistory->getHistory($this->user);
    }
}