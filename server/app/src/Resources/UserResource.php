<?php

namespace App\Resources;

use App\Model\Entities\RegisteredUser;
use App\Http\Request;
use App\Helpers\FilteringHelper;
use App\Exceptions\IllegalArgumentException;
use Doctrine\ORM\QueryBuilder;

class UserResource extends AbstractResource {

    public function read(Request $request) {
        $id = $request->args->id;
        $user = $this->getEntity($id);
        return $user;
    }

    public function readAll(Request $request) {
        $qb = $this->em->createQueryBuilder()->select('registeredUser')->from('App\Model\Entities\RegisteredUser', 'registeredUser');
        $qb = FilteringHelper::applyRules($qb, $request->params, self::getFilteringRules());
        $users = $qb->getQuery()->getResult();
        return $users;
    }

    public function create(Request $request) {
        $entity = $request->body;
        if (empty($entity->name)) {
            throw new IllegalArgumentException('Name is required!');
        }
        if (empty($entity->email)) {
            throw new IllegalArgumentException('Email is required!');
        }
        if (empty($entity->password)) {
            throw new IllegalArgumentException('Password is required!');
        }

        $user = new RegisteredUser($entity->name, $entity->email, $entity->password);

        if (property_exists($entity, 'roles')) {
            $user->setRoles($entity->roles);
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function update(Request $request) {
        $id = $request->args->id;
        if (empty($id)) {
            throw new IllegalArgumentException('Id is required!');
        }

        $entity = $request->body;
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

    public function remove(Request $request) {
        $id = $request->args->id;
        if (empty($id)) {
            throw new IllegalArgumentException('Id is required!');
        }

        $user = $this->getEntity($id);

        $this->em->remove($user);
        $this->em->flush();

        return $id;
    }

    private function getEntity($id) {
        return $this->em->createQueryBuilder()
            ->select('registeredUser')
            ->from('App\Model\Entities\RegisteredUser', 'registeredUser')
            ->andWhere('registeredUser.id = :id')->setParameter('id', $id)
            ->getQuery()->getOneOrNullResult();

        /** TODO: upgrade (example with permissions) */
        // return $request->user->getPermissions()->getVisibleRegisteredUsersQueryBuilder($request->em)
        //                   ->andWhere('registeredUser.id = :id')->setParameter('id', $this->id)
        //                   ->getQuery()->getOneOrNullResult();
    }

    private function getFilteringRules() {
        $rules = [
            'roles' => function(QueryBuilder $qb, $filterValue) {
                $roles = explode(',', $filterValue);
                return $qb->andWhere($qb->expr()->in('registeredUser.roles', $roles));
            },
        ];

        return $rules;
    }
}
