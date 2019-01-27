<?php

namespace PullUpBundle\Command;

use Aws\Credentials\Credentials;
use Aws\Sns\SnsClient;
use function GuzzleHttp\json_encode;
use PullUpDomain\Entity\User;
use PullUpDomain\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PushNotificationsCommand extends ContainerAwareCommand
{

    /**
     * SnsClient $client
     */
    private $client;

    protected function configure()
    {
        $this
            ->setName('app:push:notifications')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $credentials = new Credentials($this->getContainer()->getParameter('aws_key'), $this->getContainer()->getParameter('aws_secret'));
        $this->client = new SnsClient([
            'version' => 'latest',
            'region' => 'eu-west-1',
            'credentials' => $credentials,
        ]);

        /**
         * UserRepositoryInterface $userRepository
         */
        $userRepository = $this->getContainer()->get('pullup.user.repository');
        $users = $userRepository->getUsersWithNotifications();

        foreach ($users as $user) {
            $this->processByCircuit($user);
            $this->informAboutUpdate($user);

        }
    }

    protected function processByCircuit(User $user)
    {
        $currentCircuit = $user->getCurrentTrainingCircuit();
        $now = new \DateTime();
        $endAt = $currentCircuit->getEndAt();
        $diff = $now->diff($endAt);

        if ($diff->d === 1) {
            $message = "1 day left till your circuit end";

            $this->client->publish([
                "MessageStructure" => "json",
                "TargetArn" => $user->getAmazonArn(),
                "Message" => json_encode([
                    "default" => $message,
                    "APNS" => json_encode([
                        "aps" => [
                            "alert" => [
                                "body" => $message,
                                "type" => "circuit_end_1_day",
                            ],
                            "badge" => 12,
                            "sound" => "default",
                        ],
                    ]),
                ]),
            ]);
        }
    }

    protected function informAboutUpdate(User $user)
    {
        $currentCircuit = $user->getCurrentTrainingCircuit();

        $title = "Updated! (test)";
        $message = "Check whats new";

        $this->client->publish([
            "MessageStructure" => "json",
            "TargetArn" => $user->getAmazonArn(),
            "Message" => json_encode([
                "default" => $message,
                "APNS" => json_encode([
                    "aps" => [
                        "alert" => [
                            "title" => $title,
                            "body" => $message,
                            "type" => "application_updated",
                        ],
                        "badge" => 12,
                        "sound" => "default",
                    ],
                ]),
            ]),
        ]);
    }
}
