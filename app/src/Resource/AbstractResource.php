<?php
namespace App\Resource;

use Doctrine\ORM\EntityManager;

abstract class AbstractResource {

    protected $em = null;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    protected static function getDefaultFields() {
        return null;
    }

    // abstract getEntity

    // sorting rules

    // filtering rules
}
