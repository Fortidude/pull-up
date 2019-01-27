<?php

namespace PullUpBundle\Command;

use Aws\Credentials\Credentials;
use Aws\Sns\SnsClient;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AWSCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:aws')
            ->setDescription('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $credentials = new Credentials('AKIAJ23QMET7332KMEIQ', 'LHN2QNZSn6JxfW757dGhN6sOsO3hc5ABYPXNCEMr');
        $client = new SnsClient([
            'version' => 'latest',
            'region' => 'eu-west-1',
            'credentials' => $credentials,
        ]);

        $userDeviceToken = "444667099fc8619a05392586aed5edad10e1a4cfcdea2e181ff29620c4215474";

        $applicationARN = "arn:aws:sns:eu-west-1:453467083820:app/APNS_SANDBOX/Pullup";
        $response = $client->createPlatformEndpoint([
            'Attributes' => [],
            'CustomUserData' => 'some_data',
            'PlatformApplicationArn' => $applicationARN, // REQUIRED
            'Token' => $userDeviceToken, // REQUIRED
        ]);

        $userARN = $response['EndpointArn'];
        
dump($userARN); 

        //$arn = "arn:aws:sns:eu-west-1:453467083820:endpoint/APNS_SANDBOX/Pullup/48b4d3f6-56a0-3d96-9cc2-f1b4c235a1ee";
        //  $results = $SnSclient->listEndpointsByPlatformApplication(['PlatformApplicationArn' => $arn]);
        $client->publish([
            'Message' => "CZESC2",
            'TargetArn' => $userARN,
        ]);
    }

    private function createTopic(string $topicName)
    {
        try {
            $result = $SnSclient->createTopic([
                'Name' => $topicname,
            ]);
            var_dump($result);
        } catch (AwsException $e) {
            // output error message if fails
            error_log($e->getMessage());
        }
    }

    function list() {
        try {
            $result = $SnSclient->listTopics([
            ]);
            var_dump($result);
        } catch (AwsException $e) {
            // output error message if fails
            error_log($e->getMessage());
        }
    }
}
