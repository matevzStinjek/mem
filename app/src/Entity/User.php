<?php
namespace App\Entity;

use App\Util\Validator;
use App\Util\Util;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends Entity {

    const ROLES = ['ADMIN', 'PLEB'];
    const DEFAULT_ROELS = ['PLEB'];

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $passwordHash;

    /**
     * @ORM\Column(type="string")
     */
    private $roles;

    /**
     * @ORM\ManyToMany(targetEntity="UserGroup", inversedBy="users")
     * @ORM\JoinTable(
     *     name="userGroupsMemberships",
     *     joinColumns={@ORM\JoinColumn(name="userId", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="userGroupId", referencedColumnName="id")}
     *  )
     */
    private $userGroups;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationTimestamp;

    public function __construct($name, $email, $password, $roles) {
        $this->userGroups = new ArrayCollection;

        $this->setName($name);
        $this->setEmail($email);
        $this->setPassword($password);
        $this->setRoles($roles ?: self::DEFAULT_ROLES);
        $this->creationTimestamp = new \DateTime();
    }

    public function setName($name) {
        if (!is_string($name)) {
            throw new \Exception('Name must be a string.');
        }

        $name = trim($name);
        $name = \Normalizer::normalize($name);

        if (!strlen($name)) {
            throw new \Exception('Name cannot be empty.');
        }

        if (strlen($name) > 255) {
            throw new \Exception('Name cannot be longer than 255 characters.');
        }

        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function setEmail($email) {
        if (!is_string($email)) {
            throw new \Exception('Email must be a string.');
        }

        $email = trim($email);

        try {
            Validator::email($email);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        if (strlen($email) > 255) {
            throw new \Exception('Email cannot be longer than 255 characters.');
        }

        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setPassword($password) {
        if (!is_string($password)) {
            throw new \Exception('Password must be a string.');
        }

        $password = trim($password);
        $password = \Normalizer::normalize($password);

        if (strlen($password) < 6) {
            throw new \Exception('Password cannot be shorter than 6 characters.');
        }
        // TODO: add criteria!

        $this->passwordHash = Util::hashPassword($password);
    }

    public function isPasswordCorrect($password) {
        if (!is_string($password)) {
            throw new \Exception('Password must be a string.');
        }

        return $this->passwordHash === Util::hashPassword($password);
    }

    public function setRoles($roles) {
        if (!is_array($roles)) {
            throw new \Exception('Roles must be an array of roles as strings.');
        }

        $invalidRoles = array_udiff($roles, self::ROLES, 'strcasecmp');
        if (count($invalidRoles)) {
            throw new \Exception('Invalid roles: ' . implode(', ', $invalidRoles) . 'Valid roles include ' . implode(', ', self::ROLES));
        }

        $this->roles = implode(',', $roles);
    }

    public function getRoles() {
        return explode(',', $this->roles);
    }

    // TODO: remove when you confirm it's redundant
    public function addUserGroups($userGroups) {
        if (!is_array($userGroups)) {
            throw new \Exception('UserGroups must be an array of UserGroup entities.');
        }

        $this->userGroups = new ArrayCollection(array_merge($this->userGroups->toArray(), $userGroups->toArray()));
    }

    public function getUserGroups() {
        return $this->userGroups;
    }
}
