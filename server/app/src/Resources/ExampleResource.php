<?php

namespace App\Resources;

use App\Model\Entities\ExampleEntity;

class ExampleResource extends AbstractResource {

    public function read() {
        $example = $this->getEntity();
        return $example;
    }

    public function create() {
        $example = new ExampleEntity;

        $this->em->persist($example);
        $this->em->flush();

        return $example->getId();
    }

    private function getEntity() {
        return $this->em
            ->createQueryBuilder()
            ->select('exampleEntity')
            ->from('App\Model\Entities\ExampleEntity', 'exampleEntity')
            ->getQuery()->getOneOrNullResult();
    }
}
