<?php
namespace App\Resource;

use App\Resource\AbstractResource;

class PhotoResource extends AbstractResource {

    public function get($slug) {
        $photo = $this->entityManager->getRepository('App\Entity\Photo')->findOneBy(['slug' => $slug]);
        if ($photo) {
            return $photo->getArrayCopy();
        }
        return null;
    }

    public function getAll() {
        $photos = $this->entityManager->getRepository('App\Entity\Photo')->findAll();
        $photos = array_map(function ($photo) {
            return $photo->getArrayCopy();
        }, $photos);

        return $photos;
    }
}
