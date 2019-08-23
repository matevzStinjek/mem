<?php
namespace App\Resource;

use App\Entity\Folder;

class FolderResource extends AbstractResource {

    public function read($id) {
        $folder = $this->getEntity($id);
        return $folder ?: null;
    }

    public function readAll() {
        $qb = $this->em->createQueryBuilder()->select('folder')->from('App\Entity\Folder', 'folder');
        $folders = $qb->getQuery()->getResult();
        return $folders;
    }

    public function create($entity) {
        if (!isset($entity->name)) {
            throw new \Exception('Name is required!');
        }
        if (!isset($entity->creatorId)) {
            throw new \Exception('CreatorId is required!');
        }

        $creator = $this->em->createQueryBuilder()->select('user')->from('App\Entity\User', 'user')
                            ->andWhere('user.id = :id')->setParameter('id', $entity->creatorId)
                            ->getQuery()->getOneOrNullResult();
        if (is_null($creator)) {
            throw new \Exception('User with creatorId does not exist!');
        }

        $folder = new Folder($entity->name, $creator);

        $this->em->persist($folder);
        $this->em->flush();

        return $folder->getId();
    }

    public function update($id, $entity) {
        if (is_null($id)) {
            throw new \Exception('Id is required!');
        }

        $folder = $this->getEntity($id);

        if (isset($entity->name)) {
            $folder->setName($entity->name);
        }
        if (isset($entity->creatorId)) {
            $creator = $this->em->createQueryBuilder()->select('user')->from('App\Entity\User', 'user')
                                ->andWhere('user.id = :id')->setParameter('id', $entity->creatorId)
                                ->getQuery()->getOneOrNullResult();
            if (is_null($creator)) {
                throw new \Exception('User with creatorId does not exist!');
            }
            $folder->setCreator($creator);
        }

        $this->em->persist($folder);
        $this->em->flush();

        return $folder->getId();
    }

    public function remove($id) {
        if (is_null($id)) {
            throw new \Exception('Id is required!');
        }

        $folder = $this->getEntity($id);

        $this->em->remove($folder);
        $this->em->flush();

        return $id;
    }

    private function getEntity($id) {
        return $this->em->createQueryBuilder()
                        ->select('folder')
                        ->from('App\Entity\Folder', 'folder')
                        ->andWhere('folder.id = :id')->setParameter('id', $id)
                        ->getQuery()->getOneOrNullResult();

        /** TODO: upgrade (example with permissions) */
        // return $request->user->getPermissions()->getVisibleRegisteredUsersQueryBuilder($request->em)
        //                   ->andWhere('registeredUser.id = :id')->setParameter('id', $this->id)
        //                   ->getQuery()->getOneOrNullResult();
    }
}
