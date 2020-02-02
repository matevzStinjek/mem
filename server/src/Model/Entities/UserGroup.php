<?php

namespace App\Model\Entities;

use App\Exceptions\IllegalArgumentException;
use App\Util\Validator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="userGroups")
 */
class UserGroup extends Entity {

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="RegisteredUser", mappedBy="userGroups", cascade={"persist"})
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="FolderMembership", mappedBy="userGroup", cascade={"remove"})
     */
    private $folderMemberships;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationTimestamp;

    public function __construct($name, $users) {
        $this->setName($name);
        $this->users = new ArrayCollection;
        $this->setUsers($users);
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

    public function setUsers($users) {
        $this->users = $users;
    }

    public function getUsers() {
        return $this->users->toArray();
    }

    public function getFolderMemberships() {
        return $this->folderMemberships->toArray();
    }

    public function getCreationTimestamp() {
        return $this->creationTimestamp;
    }
}
