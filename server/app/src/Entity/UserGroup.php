<?php
namespace App\Entity;

use App\Util\Validator;
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
        try {
            Validator::name($name);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
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

    public function getCreationTimestamp() {
        return $this->creationTimestamp;
    }
}
