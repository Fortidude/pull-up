<?php

namespace PullUpDomain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;

class User extends BaseUser
{
    protected $id;
    protected $username;
    protected $token;
    protected $name;
    protected $sex;
    protected $roles;
    protected $email;
    protected $avatar;
    protected $enabled;
    protected $createdAt;
    protected $updatedAt;
    protected $expiresAt;

    protected $daysPerCircuit = 7;

    protected $firstForm;
    protected $trainingPullUps;

    protected $facebookId;

    public function __construct()
    {
        parent::__construct();
        $this->trainingPullUps = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return (string)$this->id;
    }

    /**
     * @param string $email
     * @param string $name
     * @param string $facebookId
     * @return User
     */
    public static function createUserByFacebook($email, $name, $facebookId)
    {
        $entity = new self();
        $entity->email = $email;
        $entity->username = $email;
        $entity->usernameCanonical = $email;
        $entity->name = $name;
        $entity->facebookId = $facebookId;
        $entity->plainPassword = random_bytes(10);
        $entity->roles = ['ROLE_USER'];
        $entity->enabled = true;
        $entity->expiresAt = new \DateTime('2020-12-31 23:59:59');

        return $entity;
    }

    public function updateAfterLogin($name, $avatar)
    {
        $this->name = $name;
        $this->avatar = $avatar;
    }

    public function isAdmin()
    {
        return false;
    }

    public function isEnabled()
    {
        $now = new \DateTime();

        return ($now < $this->expiresAt) && $this->enabled;
    }

    public function isFirstFormFilled()
    {
        return $this->firstForm instanceof FirstForm;
    }

    public function fillFirstForm(array $data)
    {
        if ($this->isFirstFormFilled()) {
            throw new \Exception("PULL_UP_FIRST_FORM_FILLED");
        }

        $data['user'] = $this;
        $this->firstForm = FirstForm::create($data);
    }

    public function addTrainingPullUp($route, $type, $level, $reps, $additionalInformation = [])
    {
        $this->trainingPullUps[] = TrainingPullUp::create($this, $route, $type, $level, $reps, $additionalInformation);
    }
}