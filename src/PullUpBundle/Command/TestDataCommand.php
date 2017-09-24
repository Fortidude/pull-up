<?php

namespace PullUpBundle\Command;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
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
        $this->test();
        //$this->createSomeExercise();
    }

    private function test()
    {
        $user = $this->getContainer()->get('fos_user.user_manager')->findUserBy([]);
        //$result = $this->getContainer()->get('pullup.training_pull_up.repository')->getLastFinishedRouteNumber($user);
        $result = $this->getContainer()->get('pullup.training_pull_up.service')->getCurrent($user);
        dump($result);
    }

    private function createSomeExercise()
    {
        $repository = $this->getContainer()->get('pullup.exercise.repository');

        $exercises = $repository->getList();
        if ($exercises) {
            foreach ($exercises as $exerciseEntity) {
                $this->createExerciseVariants($exerciseEntity);
            }
        } else {
            for ($i = 1; $i <= 5; $i++) {
                $exerciseEntity = Exercise::create("test_{$i}", "description_{$i}");
                $this->createExerciseVariants($exerciseEntity);
                $repository->add($exerciseEntity);
            }
        }

        $this->getContainer()->get('doctrine.orm.default_entity_manager')->flush();
    }

    private function createExerciseVariants(Exercise $exercise)
    {
        $variants = $this->getContainer()->get('doctrine')->getRepository('PullUpDomainEntity:ExerciseVariant')->getListByExercise($exercise);
        if ($variants) {
        } else {
            for ($i = 1; $i <= 5; $i++) {
                $name = "variant_name_for_{$exercise->getName()}_{$i}";
                $exercise->addExerciseVariant($name, "variant_description_{$exercise->getName()}_{$i}");

            }
        }
    }
}
