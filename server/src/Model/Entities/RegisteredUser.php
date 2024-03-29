<?php

namespace App\Model\Entities;

use App\Exceptions\IllegalArgumentException;
use App\Model\User;
use App\Model\Permissions\Roles;
use App\Model\Permissions\AdminPermissions;
use App\Model\Permissions\DemoPermissions;
use App\Model\Permissions\PublicPermissions;
use App\Model\Permissions\PermissionsUnion;
use App\Model\Permissions\SauronPermissions;
use App\Model\Permissions\RegisteredUserPermissions;
use App\Util\Validator;
use App\Util\Util;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class RegisteredUser extends Entity implements User {

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
    private $roles = [];

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
     * @ORM\OneToMany(targetEntity="FolderMembership", mappedBy="user", cascade={"remove"})
     */
    private $folderMemberships;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationTimestamp;

    private $permissionsCache;

    public function __construct($name, $email, $password) {
        $this->setName($name);
        $this->setEmail($email);
        $this->setPassword($password);
        $this->userGroups = new ArrayCollection;
        $this->folderMemberships = new ArrayCollection;
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
                new RegisteredUserPermissions($this),
                new PublicPermissions,
            ];

            $permissionsUnion = new PermissionsUnion(array_merge($roleSpecificPermissions, $defaultPermissions));
            $this->permissionsCache = $permissionsUnion;
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
        return array_unique(array_merge(...$expandedRoles));
    }

    public function setRoles($roles) {
        if (!is_array($roles)) {
            throw new IllegalArgumentException('Roles must be an array of roles as strings.');
        }

        $roles = array_map(fn($role) => strtoupper($role), $roles);
        $invalidRoles = array_diff($roles, Roles::getExistingRoles());
        if (!empty($invalidRoles)) {
            throw new IllegalArgumentException('Invalid roles: ' . implode(', ', $invalidRoles) . '. Valid roles include ' . implode(', ', Roles::getExistingRoles()));
        }

        $this->roles = $roles;
    }

    public function getRoles() {
        return $this->roles;
    }

    // TODO: remove when you confirm it's redundant
    public function addUserGroups($userGroups) {
        if (!is_array($userGroups)) {
            throw new IllegalArgumentException('UserGroups must be an array of UserGroup entities.');
        }

        $this->userGroups = new ArrayCollection(array_merge($this->userGroups->toArray(), $userGroups->toArray()));
    }

    public function getUserGroups() {
        return $this->userGroups->toArray();
    }

    public function getFolderMemberships() {
        return $this->folderMemberships->toArray();
    }

    public function getFolders() {
        $folders = array_map(fn($userGroup) => $userGroup->getFolderMemberships(), $this->getUserGroups()); // map user group's memberships
        $folders = array_merge($this->getFolderMemberships(), ...$folders);                                 // merge with user memberships
        $folders = array_map(fn($userGroupFolder) => $userGroupFolder->getFolder(), $folders);              // map memberships' folders
        return array_unique($folders);
    }

    public function getCreationTimestamp() {
        return $this->creationTimestamp;
    }

    /** Used for comparison of unique users */
    public function __toString() {
        return (string)$this->id;
    }
}
