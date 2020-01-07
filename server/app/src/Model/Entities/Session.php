<?php

namespace App\Model\Entities;

use App\Model\Entities\RegisteredUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sessions")
 */
class Session {

    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="RegisteredUser")
     * @ORM\JoinColumn(name="userId")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationTimestamp;

    public function __construct(RegisteredUser $user) {
        $this->generateId();
        $this->setUser($user);
        $this->creationTimestamp = new \DateTime;
    }

    public function getId() {
        return $this->id;
    }

    public function generateId() {
        $this->id = bin2hex(random_bytes(32));
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function getUser() {
        return $this->user;
    }

    public function getCreationTimestamp() {
        return $this->creationTimestamp;
    }
}
