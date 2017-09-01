<?php

namespace PullUpService\Handler;

use PullUpDomain\Entity\User;
use PullUpDomain\Service\AuthenticationManagerInterface;
use PullUpDomain\Service\ProfileManagerInterface;
use PullUpService\Command\LoginByFacebookCommand;

class LoginByFacebookHandler
{
    /** @var ProfileManagerInterface */
    protected $userManager;

    /** @var AuthenticationManagerInterface */
    protected $authenticationManager;

    public function __construct(ProfileManagerInterface $userManager, AuthenticationManagerInterface $authenticationManager)
    {
        $this->userManager = $userManager;
        $this->authenticationManager = $authenticationManager;
    }

    public function handle(LoginByFacebookCommand $command)
    {
        $apiUrl = "https://graph.facebook.com/me";

        $data = [
            'access_token' => $command->accessToken,
            'fields' => 'email,name,first_name,middle_name,last_name,picture'
        ];

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

        $rawResponse = curl_exec($curl);
        $response = json_decode($rawResponse, true);

        if (array_key_exists('error', $response) || !array_key_exists('email', $response)) {
            throw new \Exception("Token is not valid. {$command->accessToken}", 403);
        }

        $user = $this->userManager->findUserBy(['facebookId' => $response['id']]);
        if (!$user) {
            $user = User::createUserByFacebook($response['email'], $response['name'], $response['id']);
        }

        $avatar = isset($response['picture']) ? $response['picture']['data']['url'] : '';

        $user->setPlainPassword($command->accessToken);
        $user->updateAfterLogin($response['name'], $avatar);

        if ($user->getEmail() !== $response['email']) {
            $user->setEmail($response['email']);
        }

        $user->setLastLogin(new \DateTime("now"));
        $this->userManager->updateUser($user);

        //$this->authenticationManager->create($user);
    }
}