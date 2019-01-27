<?php

namespace PullUpService\Handler;

use Aws\Credentials\Credentials;
use Aws\Sns\SnsClient;
use PullUpDomain\Entity\User;
use PullUpService\Command\UpdateUserDeviceIdCommand;

class UpdateUserDeviceIdHandler
{
    /** @var User */
    protected $user;

    /** @var string */
    protected $awsKey;

    /** @var string */
    protected $awsSecret;

    public function __construct(User $user, string $awsKey, string $awsSecret)
    {
        $this->user = $user;
        $this->awsKey = $awsKey;
        $this->awsSecret = $awsSecret;
    }

    public function handle(UpdateUserDeviceIdCommand $command)
    {
        if ($command->deviceId === $this->user->getDeviceId()) {
            return;
        }

        $credentials = new Credentials($this->awsKey, $this->awsSecret);
        $client = new SnsClient([
            'version' => 'latest',
            'region' => 'eu-west-1',
            'credentials' => $credentials,
        ]);

        $currentUserArn = $this->user->getAmazonArn();
        if ($currentUserArn) {
            $client->deleteEndpoint([
                'EndpointArn' => $currentUserArn,
            ]);
        };

        $applicationARN = "arn:aws:sns:eu-west-1:453467083820:app/APNS_SANDBOX/Pullup";
        $response = $client->createPlatformEndpoint([
            'Attributes' => [],
            'CustomUserData' => 'some_data',
            'PlatformApplicationArn' => $applicationARN, // REQUIRED
            'Token' => $command->deviceId, // REQUIRED
        ]);

        $userARN = $response['EndpointArn'];

        $this->user->changeDeviceId($command->deviceId);
        $this->user->changeAmazonArn($userARN);
    }
}
