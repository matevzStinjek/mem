<?php

namespace App\Model\Entities;

use App\Exceptions\IllegalArgumentException;
use App\Util\Validator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="exampleEntity")
 */
class ExampleEntity extends Entity {

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="json")
     */
    private $jsn;

    public function __construct() {
        $this->name = 'test';

        $std = new \stdClass;
        $std->name = 'Home';
        $std->status = 1;
        $this->jsn = $std;
    }

    public function getName() {
        return $this->name;
    }

    public function getJsn() {
        return $this->jsn;
    }
}
