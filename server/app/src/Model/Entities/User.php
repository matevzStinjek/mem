<?php

namespace App\Model\Entities;

use App\Exceptions\IllegalArgumentException;
use App\Model\Permissions\Roles;
use App\Model\Permissions\AdminPermissions;
use App\Model\Permissions\WtPermissions;
use App\Model\Permissions\DemoPermissions;
use App\Model\Permissions\PublicPermissions;
use App\Model\Permissions\PermissionsUnion;
use App\Model\Permissions\SauronPermissions;
use App\Model\Permissions\UserPermissions;
use App\Util\Validator;
use App\Util\Util;
use Doctrine\Common\Collections\ArrayCollection;
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
    private $salt;

    /**
     * @ORM\Column(type="set")
     */
    private $roles;

    /**
     * @ORM\ManyToMany(targetEntity="UserGroup", inversedBy="users")
     * @ORM\JoinTable(
     *     name="userGroupsMemberships",
     *     joinColumns={@ORM\JoinColumn(name="userId", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="userGroupId", referencedColumnName="id")}
     * )
     */
    private $userGroups;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationTimestamp;

    private $permissionsCache;

    public function __construct($name, $email, $password) {
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

        $this->salt = openssl_random_pseudo_bytes(16);
        $this->passwordHash = Util::hashPassword($password, $this->salt);
    }

    public function isPasswordCorrect($password) {
        if (!is_string($password)) {
            throw new IllegalArgumentException('Password must be a string.');
        }

        return $this->passwordHash === Util::hashPassword($password, $this->salt);
    }

    public function getPermissions() {
        if (empty($this->permissionsCache)) {
            $roleSpecificPermissions = array_map([$this, 'createPermissionsFromRole'], $this->getEffectiveRoles());
            $defaultPermissions = [
                new UserPermissions($this),
                new PublicPermissions,
            ];
            error_log(var_dump(array_merge($roleSpecificPermissions, $defaultPermissions)));

            $permissionsUnion = new PermissionsUnion(array_merge($roleSpecificPermissions, $defaultPermissions));
            $this->permissionsCache = '$permissionsUnion';
        }

        return $this->permissionsCache;
    }

    private function createPermissionsFromRole($role) {
        switch ($role) {
            case Roles::ROLE_ADMIN:
                return new AdminPermissions;
            case Roles::ROLE_SAURON:
                return new SauronPermissions;
            case Roles::ROLE_DEMO:
                return new DemoPermissions;
        }
    }

    public function expandRole($role) {
        return array_merge([$role], Roles::getImpliedRoles($role));
    }

    private function getEffectiveRoles() {
        $expandedRoles = array_map([$this, 'expandRole'], $this->getRoles());
        return array_merge(...$expandedRoles);
    }

    public function setRoles($roles) {
        if (!is_array($roles)) {
            throw new \Exception('Roles must be an array of roles as strings.');
        }

        $roles = array_map(function($role) { return strtoupper($role); }, $roles);
        $invalidRoles = array_diff($roles, Roles::getExistingRoles());
        if (!empty($invalidRoles)) {
            throw new \Exception('Invalid roles: ' . implode(', ', $invalidRoles) . '. Valid roles include ' . implode(', ', Roles::getExistingRoles()));
        }

        $this->roles = $roles;
    }

    public function getRoles() {
        return $this->roles;
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
