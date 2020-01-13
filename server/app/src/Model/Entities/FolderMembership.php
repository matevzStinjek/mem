<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="folderMemberships")
 */
class FolderMembership extends Entity {

    /**
     * @ORM\ManyToOne(targetEntity="RegisteredUser", inversedBy="folderMemberships", fetch="EAGER")
     * @ORM\JoinColumn(name="userId")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="UserGroup", inversedBy="folderMemberships", fetch="EAGER")
     * @ORM\JoinColumn(name="userGroupId")
     */
    private $userGroup;

    /**
     * @ORM\ManyToOne(targetEntity="Folder", inversedBy="memberships", fetch="EAGER")
     * @ORM\JoinColumn(name="folderId")
     */
    private $folder;

    /**
     * @ORM\Column(type="boolean")
     */
    private $readOnly = false;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $creationTimestamp;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastModificationTimestamp;

    public function __construct(Folder $folder) {
        $this->folder = $folder;
        $this->creationTimestamp = new \DateTime;
    }

    public function setUser(RegisteredUser $user) {
        $this->user = $user;
    }

    public function setUserGroup(UserGroup $userGroup) {
        $this->userGroup = $userGroup;
    }

    public function getUsers() {
        if (isset($this->userGroup))
            return $this->userGroup->getUsers();
        if (isset($this->user))
            return [$this->user];
        return [];
    }

    public function getFolder() {
        return $this->folder;
    }

    public function setIsReadOnly($readOnly) {
        $this->readOnly = $readOnly;
    }

    public function isReadOnly() {
        return $this->readOnly;
    }
 
    public function getCreationTimestamp() {
        return $this->creationTimestamp;
    }

    public function updateLastModificationTimestamp() {
        $this->lastModificationTimestamp = new \DateTime;
    }

    public function getLastModificationTimestamp() {
        return $this->lastModificationTimestamp ?? $this->creationTimestamp;
    }
}
