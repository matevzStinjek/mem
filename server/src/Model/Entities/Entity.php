<?php

namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class Entity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id = null;

    public function __construct() {
    }

    public function getId() {
        return $this->id;
    }
}
