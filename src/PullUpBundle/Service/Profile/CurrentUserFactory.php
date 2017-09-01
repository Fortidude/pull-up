<?php

namespace PullUpBundle\Service\Profile;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CurrentUserFactory
{
    protected $tokenStorage;

    /**
     * CurrentUserFactory constructor.
     * @param TokenStorageInterface $storageInterface
     */
    public function __construct(TokenStorageInterface $storageInterface)
    {
        $this->tokenStorage = $storageInterface;
    }

    /**
     * @return mixed|void
     */
    public function getUser()
    {

        if (null === $token = $this->tokenStorage->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            return;
        }

        return $user;
    }
}
