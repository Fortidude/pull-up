<?php

namespace PullUpService\Handler;

use Aws\Credentials\Credentials;
use Aws\Sns\SnsClient;
use PullUpDomain\Entity\User;
use PullUpService\Command\UpdateUserDeviceIdCommand;
use PullUpDomain\Repository\UserRepositoryInterface;

class UpdateUserDeviceIdHandler
{
    /** @var UserRepositoryInterface */
    protected $userRepository;

    /** @var User */
    protected $user;

    /** @var string */
    protected $awsKey;

    /** @var string */
    protected $awsSecret;

    public function __construct(UserRepositoryInterface $userRepository, User $user, string $awsKey, string $awsSecret)
    {
        $this->userRepository = $userRepository;
        $this->user = $user;
        $this->awsKey = $awsKey;
        $this->awsSecret = $awsSecret;
    }

    public function handle(UpdateUserDeviceIdCommand $command)
    {
       if ($command->deviceId === $this->user->getDeviceId()) {
           return;
       }

        $userAssignedToThisDevice = $this->userRepository->findOneBy(['deviceId' => $command->deviceId]);
        if ($userAssignedToThisDevice) {
            $userAssignedToThisDevice->removeDeviceId();
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

        $applicationARNDev = "arn:aws:sns:eu-west-1:453467083820:app/APNS_SANDBOX/Pullup";
        $applicationARNProd = "arn:aws:sns:eu-west-1:453467083820:app/APNS/Pull-and-push";
        $response = $client->createPlatformEndpoint([
            'Attributes' => [],
            'CustomUserData' => 'some_data',
            'PlatformApplicationArn' => $applicationARNProd, // REQUIRED
            'Token' => $command->deviceId, // REQUIRED
        ]);

        $userARN = $response['EndpointArn'];

        $this->user->changeDeviceId($command->deviceId);
        $this->user->changeAmazonArn($userARN);
    }
}
