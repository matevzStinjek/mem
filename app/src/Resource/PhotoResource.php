<?php
namespace App\Resource;

use App\Entity\Photo;
use App\Resource\AbstractResource;

class PhotoResource extends AbstractResource {

    public function read($id) {
        $photo = $this->getEntity($id);
        return $photo ? $photo->getArrayCopy() : null;
    }

    public function readAll() {
        $photos = $this->getEntities();
        $photos = array_map(function ($photo) {
            return $photo->getArrayCopy();
        }, $photos);

        return $photos;
    }

    public function create($entity) {
        if (!isset($entity->slug)) {
            throw new \Exception('Slug is required!');
        }

        $photo = new Photo($entity->slug);
        $photo->setTitle($entity->title);

        $this->em->persist($photo);
        $this->em->flush();

        return $photo;
    }

    public function update($entity) {
        if (!isset($entity->id)) {
            throw new \Exception('Id is required!');
        }

        $photo = $this->getEntity($entity->id);

        if (isset($entity->image)) {
            $photo->setImage($entity->image);
        }

        $this->em->persist($photo);
        $this->em->flush();

        return $photo;
    }

    public function remove($entity) {
        if (!isset($entity->id)) {
            throw new \Exception('Id is required!');
        }

        $photo = $this->getEntity($entity->id);

        $this->em->remove($photo);
        $this->em->flush();
    }

    // CRUD: create read update delete

    private function getEntity($id) {
        return $this->em->createQueryBuilder()
                        ->select('photo')
                        ->from('App\Entity\Photo', 'photo')
                        ->andWhere('photo.id = :id')->setParameter('id', $id)
                        ->getQuery()->getOneOrNullResult();

        /** another example */
        // return $this->em->getRepository(self::ENTITY)->findOneBy(['id' => $id]);

        /** upgrade to this */
        // return $request->user->getPermissions()->getVisibleRegisteredUsersQueryBuilder($request->em)
        //                   ->andWhere('registeredUser.id = :id')->setParameter('id', $this->id)
        //                   ->getQuery()->getOneOrNullResult();
    }

    private function getEntities() {
        return $this->em->createQueryBuilder()
                        ->select('photo')
                        ->from('App\Entity\Photo', 'photo')
                        ->getQuery()->getResult();
    }
}
