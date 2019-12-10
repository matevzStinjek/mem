<?php

namespace App\Model\Entities;

use App\Exceptions\IllegalArgumentException;
use App\Model\Permissions\Roles;
use App\Model\Permissions\AdminPermissions;
use App\Model\Permissions\DemoPermissions;
use App\Model\Permissions\UserPermissions;
use App\Model\Permissions\SauronPermissions;
use App\Util\Validator;
use App\Util\Util;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends Entity {

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

    private $permissionsCache;

    public function __construct($name, $email, $password, $roles) {
        $this->userGroups = new ArrayCollection;

        $this->setName($name);
        $this->setEmail($email);
        $this->setPassword($password);
        $this->creationTimestamp = new \DateTime;
    }

    public function setName($name) {
        try {
            Validator::name($name);
        } catch (IllegalArgumentException $e) {
            throw new IllegalArgumentException($e->getMessage());
        }

        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function setEmail($email) {
        try {
            Validator::email($email);
        } catch (IllegalArgumentException $e) {
            throw new IllegalArgumentException($e->getMessage());
        }

        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setPassword($password) {
        try {
            Validator::password($password);
        } catch (IllegalArgumentException $e) {
            throw new IllegalArgumentException($e->getMessage());
        }

        $this->passwordHash = Util::hashPassword($password);
    }

    public function isPasswordCorrect($password) {
        if (!is_string($password)) {
            throw new IllegalArgumentException('Password must be a string.');
        }

        return $this->passwordHash === Util::hashPassword($password);
    }

    public function getPermissions() {
        if (empty($this->permissionsCache)) {
            $roleSpecificPermissions = array_map([$this, 'createPermissionsFromRole'], $this->getEffectiveRoles());
            $defaultPermissions = [
                new PublicPermissions,
                // new UserPermissions($this)
            ];

            $permissionUnion = new PermissionsUnion(array_merge($roleSpecificPermissions, $defaultPermissions));
            $this->permissionsCache = $permissionUnion;
        }

        return $this->permissionsCache;
    }

    private function createPermissionsFromRole($role) {
        switch($role) {
            case Roles::ROLE_ADMIN:
                return new AdminPermissions;
            case Roles::ROLE_DEMO:
                return new DemoPermissions;
            case Roles::ROLE_SAURON:
                return new SauronPermissions;
        }
    }

    public function expandRole($role) {
        return array_merge([$role], Roles::getImpliedRoles($role));
    }

    public function getEffectiveRoles() {
        $expandedRoles = array_map([$this, 'expandRole'], $this->getRoles());
        return array_merge(...$expandedRoles);
    }

    // TODO: Doctrine Type array
    public function setRoles($roles) {
        if (!is_array($roles)) {
            throw new \Exception('Roles must be an array of roles as strings.');
        }

        $invalidRoles = array_udiff($roles, Roles::getExistingRoles(), 'strcasecmp');
        if (!empty($invalidRoles)) {
            throw new \Exception('Invalid roles: ' . implode(', ', $invalidRoles) . 'Valid roles include ' . implode(', ', Roles::getExistingRoles()));
        }

        $this->roles = implode(',', $roles);
    }

    // TODO: Doctrine Type array
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

    public function getCreationTimestamp() {
        return $this->creationTimestamp;
    }
}
