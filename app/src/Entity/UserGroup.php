<?php
namespace App\Entity;

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
     * @ORM\ManyToMany(targetEntity="User", mappedBy="userGroups")
     */
    private $users;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationTimestamp;

    public function __construct($name, $users) {
        $this->users = new ArrayCollection();

        $this->setName($name);
        $this->addUsers($users);
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

    public function addUsers($users) {
        if (!is_array($users)) {
            throw new \Exception('Users must be an array of User entities.');
        }

        $this->users = new ArrayCollection(array_merge($this->users->toArray(), $users->toArray()));
    }

    public function getUsers() {
        return $this->users;
    }
}
