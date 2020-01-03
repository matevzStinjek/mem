<?php

namespace App\Resources;

use App\Model\Entities\User;
use App\Http\Request;
use App\Helpers\FilteringHelper;
use Doctrine\ORM\QueryBuilder;

class UserResource extends AbstractResource {

    public function read(Request $request) {
        $id = $request->args->id;
        $user = $this->getEntity($id);
        return $user;
    }

    public function readAll(Request $request) {
        $qb = $this->em->createQueryBuilder()->select('user')->from('App\Entity\User', 'user');
        $qb = FilteringHelper::applyRules($qb, $request->params, self::getFilteringRules());
        $users = $qb->getQuery()->getResult();
        return $users;
    }

    public function create(Request $request) {
        $entity = $request->body;
        if (empty($entity->name)) {
            throw new \Exception('Name is required!');
        }
        if (empty($entity->email)) {
            throw new \Exception('Email is required!');
        }
        if (empty($entity->password)) {
            throw new \Exception('Password is required!');
        }

        $user = new User($entity->name, $entity->email, $entity->password);

        if (property_exists($entity, 'roles') && $user->getPermissions()->canSetUserRoles()) {
            $user->setRoles($entity->roles);
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function update(Request $request) {
        $id = $request->args->id;
        if (empty($id)) {
            throw new \Exception('Id is required!');
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
            throw new \Exception('Id is required!');
        }

        $user = $this->getEntity($id);

        $this->em->remove($user);
        $this->em->flush();

        return $id;
    }

    private function getEntity($id) {
        return $this->em
            ->createQueryBuilder()
            ->select('user')
            ->from('App\Model\Entities\User', 'user')
            ->andWhere('user.id = :id')->setParameter('id', $id)
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
                return $qb->andWhere($qb->expr()->in('user.roles', $roles));
            },
        ];

        return $rules;
    }
}
