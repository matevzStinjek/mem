<?php

namespace App\Resources;

use App\Exceptions\IllegalArgumentException;
use App\Exceptions\UserException;
use App\Helpers\FilteringHelper;
use App\Helpers\PaginationHelper;
use App\Helpers\SortingHelper;
use App\Http\Request;
use App\Model\Entities\UserGroup;
use Doctrine\ORM\QueryBuilder;

class UserGroupResource extends AbstractResource {

    public function read(Request $request) {
        $userGroup = $this->getEntity($request);
        if (empty($userGroup)) {
            throw new UserException('User group not found');
        }
        return $userGroup;
    }

    public function readAll(Request $request) {
        $qb = $request->user->getPermissions()->getSearchableUserGroupsQueryBuilder($this->em);
        $qb = FilteringHelper::filterByRules($qb, $request->params, self::getFilteringRules());
        $qb = SortingHelper::orderByRules($qb, $request->params, self::getSortingRules());

        $userGroups = PaginationHelper::returnPage($qb, $request);
        return $userGroups;
    }

    public function create(Request $request) {
        $entity = $request->body;
        if (empty($entity->name)) {
            throw new IllegalArgumentException('Name is required!');
        }
        if (empty($entity->users)) {
            throw new IllegalArgumentException('Users are required!');
        }

        $users = array_filter(array_map(fn($userId) => $this->em->find('App\Model\Entities\RegisteredUser', $userId), $entity->users));
        $existingUserIds = array_map(fn($user) => $user->getId(), $users);
        $nonExistingUserIds = array_diff($existingUserIds, $entity->users);
        // $unclaimedUsers = array_map(fn($userId) => new UnclaimedUser(), $nonExistingUserIds);
        // $users = array_merge($users, $unclaimedUsers);


        $userGroup = new UserGroup($entity->name, $users);

        $this->em->persist($userGroup);
        $this->em->flush();

        return $userGroup;
    }

    public function update(Request $request) {
        $userGroup = $this->getEntity($request);
        if (empty($userGroup)) {
            throw new UserException('User group not found');
        }

        $entity = $request->body;
        if (isset($entity->name)) {
            $userGroup->setName($entity->name);
        }
        if (property_exists($entity, 'users') && is_array($entity->users)) {
            $users = array_filter(array_map(fn($userId) => $this->em->find('App\Model\Entities\RegisteredUser', $userId), $entity->users));
            $existingUserIds = array_map(fn($user) => $user->getId(), $users);
            $nonExistingUserIds = array_diff($existingUserIds, $entity->users);
            // $unclaimedUsers = array_map(fn($userId) => new UnclaimedUser(), $nonExistingUserIds);
            // $users = array_merge($users, $unclaimedUsers);
            $userGroup->setUsers($users);
        }

        $this->em->persist($userGroup);
        $this->em->flush();

        return $userGroup;
    }

    public function remove(Request $request) {
        $userGroup = $this->getEntity($request);
        if (empty($userGroup)) {
            throw new UserException('User group not found');
        }

        $this->em->remove($userGroup);
        $this->em->flush();

        return $userGroup;
    }

    private function getEntity(Request $request) {
        $id = $request->args->id;

        return $request->user->getPermissions()->getVisibleUserGroupsQueryBuilder($this->em)
            ->andWhere('userGroup.id = :id')->setParameter('id', $id)
            ->getQuery()->getOneOrNullResult();
    }

    private static function getFilteringRules() {
        $rules = [
            // 'hasMember' => function(QueryBuilder $qb, $filterValue) {
            //     $qb->andWhere(?);
            //     $qb->setParameter(?);
            //     return $qb;
            // },
        ];

        return $rules;
    }

    private static function getSortingRules() {
        $rules = [
            'creationTimestamp' => function($qb, $direction) {
                return $qb->addOrderBy('userGroup.creationTimestamp', $direction);
            },
            'name' => function($qb, $direction) {
                return $qb->addOrderBy('userGroup.name', $direction);
            },
        ];

        return $rules;
    }
}
