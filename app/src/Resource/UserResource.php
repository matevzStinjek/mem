<?php
namespace App\Resource;

use App\Entity\User;
use App\Resource\AbstractResource;

class UserResource extends AbstractResource {

    public function read($id) {
        $user = $this->getEntity($id);
        return $user ?: null;
    }

    public function readAll() {
        $users = $this->getEntities();
        return $users;
    }

    public function create($entity) {
        if (!isset($entity->name)) {
            throw new \Exception('Name is required!');
        }
        if (!isset($entity->email)) {
            throw new \Exception('Email is required!');
        }
        if (!isset($entity->password)) {
            throw new \Exception('Password is required!');
        }

        $user = new User($entity->name, $entity->email, $entity->password);

        $this->em->persist($user);
        $this->em->flush();

        return $user->getId();
    }

    public function update($id, $entity) {
        if (is_null($id)) {
            throw new \Exception('Id is required!');
        }

        $user = $this->getEntity($id);

        if (isset($entity->name)) {
            $user->setName($entity->name);
        }
        if (isset($entity->email)) {
            $user->setEmail($entity->email);
        }
        if (isset($entity->password)) {
            $user->setPassword($entity->password);
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user->getId();
    }

    public function remove($id) {
        if (is_null($id)) {
            throw new \Exception('Id is required!');
        }

        $user = $this->getEntity($id);

        $this->em->remove($user);
        $this->em->flush();

        return $id;
    }

    // // CRUD: create read update delete

    private function getEntity($id) {
        return $this->em->createQueryBuilder()
                        ->select('user')
                        ->from('App\Entity\User', 'user')
                        ->andWhere('user.id = :id')->setParameter('id', $id)
                        ->getQuery()->getOneOrNullResult();

        /** TODO: upgrade (example with permissions) */
        // return $request->user->getPermissions()->getVisibleRegisteredUsersQueryBuilder($request->em)
        //                   ->andWhere('registeredUser.id = :id')->setParameter('id', $this->id)
        //                   ->getQuery()->getOneOrNullResult();
    }

    private function getEntities() {
        return $this->em->createQueryBuilder()
                        ->select('user')
                        ->from('App\Entity\User', 'user')
                        ->getQuery()->getResult();
    }
}
