<?php

namespace App\Resources;

use Doctrine\ORM\EntityManager;

abstract class AbstractResource {

    protected $em = null;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    protected static function getDefaultFields() {
        return null;
    }

    // shared sorting rules

    // shared filtering rules

    // abstract getEntity
}
