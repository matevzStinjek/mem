<?php

namespace App\Model\Entities;

use App\Exceptions\IllegalArgumentException;
use App\Util\Validator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="folders")
 */
class Folder extends Entity {

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="RegisteredUser")
     * @ORM\JoinColumn(name="creatorId")
     */
    private $creator;

    /**
     * @ORM\OneToMany(targetEntity="FolderMembership", mappedBy="folder")
     * cascade? orphanRemoval?
     */
    protected $memberships;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationTimestamp;

    public function __construct($name, $creator) {
        $this->setName($name);
        $this->creator = $creator;
        $this->memberships = new ArrayCollection;
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

    public function getCreator() {
        return $this->creator;
    }

    public function getMembers() {
        $users = array_map(fn($membership) => $membership->getUsers(), $this->memberships->toArray());
        $users = array_merge(...$users);
        return array_unique($users);
    }

    public function getCreationTimestamp() {
        return $this->creationTimestamp;
    }

    /** Used for comparison of unique folders */
    public function __toString() {
        return (string)$this->id;
    }
}
