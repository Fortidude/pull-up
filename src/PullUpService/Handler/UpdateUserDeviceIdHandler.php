<?php

namespace PullUpService\Handler;

use PullUpDomain\Entity\User;
use PullUpService\Command\UpdateUserDeviceIdCommand;

use Aws\Credentials\Credentials;
use Aws\Sns\SnsClient;

class UpdateUserDeviceIdHandler
{
    /** @var User */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(UpdateUserDeviceIdCommand $command)
    {
        $credentials = new Credentials('AKIAJ23QMET7332KMEIQ', 'LHN2QNZSn6JxfW757dGhN6sOsO3hc5ABYPXNCEMr');
        $client = new SnsClient([
            'version' => 'latest',
            'region' => 'eu-west-1',
            'credentials' => $credentials,
        ]);

        $currentUserArn = $this->user->getAmazonArn();
        if ($currentUserArn) {
            $client->deleteEndpoint([
                'EndpointArn' => $currentUserArn
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
