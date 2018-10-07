<?php

namespace PullUpBundle\Command;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use PullUpDomain\Entity\Circuit;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use PullUpDomain\Entity\Exercise;

class ProcessCircuitsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:process:circuits')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $circuits = $em->getRepository('PullUpDomainEntity:Circuit')->getEndedYesterday();
        $logger = $this->getContainer()->get('monolog.logger.command_process_circuits');

        foreach ($circuits as $circuit) {
            if (!$circuit->isFinished()) {
                $logger->debug("FINISHED! User: \"{$circuit->getUser()->getId()}\", circuit: \"{$circuit->getId()}\"");
                $circuit->finish();
                $em->flush();
            }

            $current = $circuit->getUser()->getCurrentTrainingCircuit();
            if ($current->justCreated) {
                $logger->debug("CREATED! User: \"{$circuit->getUser()->getId()}\", new: \"{$current->getId()}\"");
                $em->persist($current);
                $em->flush();
            }
        }
    }
}

