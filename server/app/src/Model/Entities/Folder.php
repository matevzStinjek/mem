<?php

namespace App\Model\Entities;

use App\Exceptions\IllegalArgumentException;
use App\Util\Validator;
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
     * @ORM\Column(type="datetime")
     */
    private $creationTimestamp;

    public function __construct($name, $creator) {
        $this->setName($name);
        $this->setCreator($creator);
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

    public function setCreator($creator) {
        $this->creator = $creator;
    }

    public function getCreator() {
        return $this->creator;
    }

    public function getCreationTimestamp() {
        return $this->creationTimestamp;
    }
}
