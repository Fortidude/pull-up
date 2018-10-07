<?php

namespace PullUpBundle\Service\Profile;

use Doctrine\Common\Persistence\ObjectManager;

use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManager as BaseUserManager;
use FOS\UserBundle\Util\CanonicalFieldsUpdater;
use FOS\UserBundle\Util\PasswordUpdaterInterface;

use PullUpDomain\Entity\User;
use PullUpDomain\Service\ProfileManagerInterface;
use PullUpDomain\Repository\UserRepositoryInterface;

class ProfileManager extends BaseUserManager implements UserManagerInterface, ProfileManagerInterface
{
    protected $repository;
    protected $objectManager;

    /**
     * ProfileManager constructor.
     * @param PasswordUpdaterInterface $passwordUpdater
     * @param CanonicalFieldsUpdater $canonicalFieldsUpdater
     * @param ObjectManager $om
     * @param UserRepositoryInterface $repository
     */
    public function __construct(
        PasswordUpdaterInterface $passwordUpdater,
        CanonicalFieldsUpdater $canonicalFieldsUpdater,
        ObjectManager $om,
        UserRepositoryInterface $repository
    )
    {
        parent::__construct($passwordUpdater, $canonicalFieldsUpdater);
        $this->objectManager = $om;
        $this->repository = $repository;
    }

    /**
     * @return UserRepositoryInterface
     */
    protected function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param UserInterface $user
     */
    public function deleteUser(UserInterface $user)
    {
        $this->repository->remove($user);
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        return 'UmowieniDomain\Entity\User';
    }

    public function findUserByEmail($email)
    {
        return $this->repository->findOneBy(['emailCanonical' => $email]);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserBy(array $criteria)
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    /**
     * @param array $usernames
     * @return bool
     */
    public function checkIfExist(array $usernames): bool
    {
        return $this->getRepository()->checkIfExist($usernames);
    }

    /**
     * {@inheritdoc}
     */
    public function findUsers()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function reloadUser(UserInterface $user)
    {
        $this->objectManager->refresh($user);
    }

    public function update(User $user)
    {
        $this->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function updateUser(UserInterface $user, $andFlush = true)
    {
        $this->updateCanonicalFields($user);
        $this->updatePassword($user);

        $this->objectManager->persist($user);
        if ($andFlush) {
            $this->objectManager->flush();
        }
    }
}