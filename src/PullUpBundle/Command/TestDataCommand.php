<?php

namespace PullUpBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use PullUpDomain\Entity\Exercise;

class TestDataCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:test:data')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->createSomeExercise();
    }

    private function createSomeExercise()
    {
        $repository = $this->getContainer()->get('pullup.exercise.repository');

        for ($i = 1; $i <= 5; $i++) {
            $exerciseEntity = Exercise::create("test_{$i}");
            $repository->add($exerciseEntity);
        }
    }
}
