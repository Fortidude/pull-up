<?php

namespace PullUpDomain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @TODO BaseUser out
 *
 * Class User
 * @package PullUpDomain\Entity
 */
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

    /** @var Circuit[] */
    protected $circuits;

    protected $trainingPullUpFirstForm;
    protected $trainingPullUps;

    protected $facebookId;

    public function __construct()
    {
        parent::__construct();

        /**
         * @TODO ArrayCollection as interface?
         */
        $this->trainingPullUps = new ArrayCollection();
        $this->circuits = new ArrayCollection();
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
        $entity->circuits[] = Circuit::create($entity);

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

    public function isTrainingPullUpFirstFormFilled()
    {
        return $this->trainingPullUpFirstForm instanceof TrainingPullUpFirstForm;
    }

    public function fillTrainingPullUpFirstForm(array $data)
    {
        if ($this->isTrainingPullUpFirstFormFilled()) {
            throw new \Exception("PULL_UP_FIRST_FORM_FILLED");
        }

        $data['user'] = $this;
        $this->trainingPullUpFirstForm = TrainingPullUpFirstForm::create($data);
    }

    public function addTrainingPullUp($route, $type, $level, $reps, $additionalInformation = [])
    {
        $this->trainingPullUps[] = TrainingPullUp::create($this, $route, $type, $level, $reps, $additionalInformation);
    }

    public function addGoal()
    {
        // @todo refactoring CreateGoalHandler
    }

    /**
     * @return int
     */
    public function getDaysPerCircuit(): int
    {
        return $this->daysPerCircuit;
    }

    /**
     * @param int $days
     * @return bool
     * @throws \Exception
     */
    public function changeDaysAmountPerCircuit(int $days)
    {
        if ($days === $this->daysPerCircuit) {
            return false;
        }

        if ($days <= 0) {
            throw new \Exception("DOMAIN.NEW_CIRCUIT_DURATION_INVALID");
        }

        $this->daysPerCircuit = $days;
        return true;
    }

    /**
     * @return Circuit
     */
    public function getCurrentTrainingCircuit()
    {
        foreach ($this->circuits as $circuit) {
            if ($circuit->isCurrent()) {
                return $circuit;
            }
        }

        $circuit = Circuit::create($this);
        $this->circuits[] = $circuit;

        return $circuit;
    }

    public function getTrainingCircuitByDate(\DateTime $dateTime)
    {
        foreach ($this->circuits as $circuit) {
            if ($circuit->isForDate($dateTime)) {
                return $circuit;
            }
        }

        $circuit = Circuit::createByStartDate($this, $dateTime);
        $this->circuits[] = $circuit;

        return $circuit;
    }
}
