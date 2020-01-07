<?php

namespace App\Resources;

use App\Exceptions\IllegalArgumentException;
use App\Exceptions\UserException;
use App\Helpers\FilteringHelper;
use App\Helpers\PaginationHelper;
use App\Helpers\SortingHelper;
use App\Http\Request;
use App\Model\Entities\RegisteredUser;
use Doctrine\ORM\QueryBuilder;

class UserResource extends AbstractResource {

    public function read(Request $request) {
        $user = $this->getEntity($request);
        return $user;
    }

    public function readAll(Request $request) {
        $qb = $request->user->getPermissions()->getSearchableRegisteredUsersQueryBuilder($this->em);
        $qb = FilteringHelper::filterByRules($qb, $request->params, self::getFilteringRules());
        $qb = SortingHelper::orderByRules($qb, $request->params, self::getSortingRules());

        $users = PaginationHelper::returnPage($qb, $request);
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

        $user = $this->em->getRepository('App\Model\Entities\RegisteredUser')->findOneByEmail($entity->email);
        if (isset($user)) {
            throw new UserException('User with this email already exists');
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
        $entity = $request->body;
        $user = $this->getEntity($request);

        if (isset($entity->name)) {
            $user->setName($entity->name);
        }
        if (isset($entity->password)) {
            $user->setPassword($entity->password);
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function remove(Request $request) {
        $user = $this->getEntity($request);

        $this->em->remove($user);
        $this->em->flush();

        return $user;
    }

    private function getEntity(Request $request) {
        $id = $request->args->id;

        return $request->user->getPermissions()->getVisibleRegisteredUsersQueryBuilder($this->em)
            ->andWhere('registeredUser.id = :id')->setParameter('id', $id)
            ->getQuery()->getOneOrNullResult();
    }

    private static function getFilteringRules() {
        $rules = [
            'roles' => function(QueryBuilder $qb, $filterValue) {
                $roles = explode(',', $filterValue);
                foreach ($roles as $index => $role) {
                    $orX[] = "registeredUser.roles LIKE :val$index";
                    $qb->setParameter("val$index", "%$role%");
                }
                // TODO: fix, not working, not sure why
                $qb->andWhere(call_user_func_array([$qb->expr(), 'orX'], $orX));
                return $qb;
            },
        ];

        return $rules;
    }

    private static function getSortingRules() {
        $rules = [
            'creationTimestamp' => function($qb, $direction) {
                return $qb->addOrderBy('registeredUser.creationTimestamp', $direction);
            },
            'name' => function($qb, $direction) {
                return $qb->addOrderBy('registeredUser.name', $direction);
            },
        ];

        return $rules;
    }
}
