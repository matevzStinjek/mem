<?php

namespace App\Resources;

use App\Exceptions\IllegalArgumentException;
use App\Exceptions\UserException;
use App\Helpers\FilteringHelper;
use App\Helpers\PaginationHelper;
use App\Helpers\SortingHelper;
use App\Http\Request;
use App\Model\Entities\Folder;
use App\Model\Entities\FolderMembership;

class FolderResource extends AbstractResource {

    public function read(Request $request) {
        $folder = $this->getEntity($request);
        if (empty($folder)) {
            throw new UserException('Folder not found');
        }
        return $folder;
    }

    public function readAll(Request $request) {
        $qb = $request->user->getPermissions()->getSearchableFoldersQueryBuilder($this->em);
        $qb = FilteringHelper::filterByRules($qb, $request->params, self::getFilteringRules());
        $qb = SortingHelper::orderByRules($qb, $request->params, self::getSortingRules());

        $folders = PaginationHelper::returnPage($qb, $request);
        return $folders;
    }

    public function create(Request $request) {
        $entity = $request->body;
        if (empty($entity->name)) {
            throw new IllegalArgumentException('Name is required!');
        }

        $folder = new Folder($entity->name, $request->user);

        // extract to helper
        if (property_exists($entity, 'userMemberships')) {
            $users = array_filter(array_map(fn($userId) => $this->em->find('App\Model\Entities\RegisteredUser', $userId), $entity->userMemberships));
            $membershipsViaUser = array_map(fn($user) => new FolderMembership($folder, $user), $users);
        }
        if (property_exists($entity, 'userGroupMemberships')) {
            $userGroups = array_filter(array_map(fn($useGrouprId) => $this->em->find('App\Model\Entities\UserGroups', $useGrouprId), $entity->userGroupMemberships));
            $membershipsViaUserGroup = array_map(fn($userGroup) => new FolderMembership($folder, $userGroup), $userGroups);
        }
        $memberships = array_merge($membershipsViaUser ?? [], $membershipsViaUserGroup ?? []);
        $memberships[] = new FolderMembership($folder, $request->user); // TODO: don't add owner twice
        $folder->setMemberships($memberships);

        $this->em->persist($folder);
        $this->em->flush();

        return $folder;
    }

    public function update(Request $request) {
        $folder = $this->getEntity($request);
        if (empty($folder)) {
            throw new UserException('User not found');
        }
        
        $entity = $request->body;
        if (isset($entity->name)) {
            $folder->setName($entity->name);
        }

        // extract to helper
        if (property_exists($entity, 'userMemberships')) {
            $users = array_filter(array_map(fn($userId) => $this->em->find('App\Model\Entities\RegisteredUser', $userId), $entity->userMemberships));
            $membershipsViaUser = array_map(fn($user) => new FolderMembership($folder, $user), $users);
        }
        if (property_exists($entity, 'userGroupMemberships')) {
            $userGroups = array_filter(array_map(fn($useGrouprId) => $this->em->find('App\Model\Entities\UserGroup', $useGrouprId), $entity->userGroupMemberships));
            $membershipsViaUserGroup = array_map(fn($userGroup) => new FolderMembership($folder, $userGroup), $userGroups);
        }
        $memberships = array_merge($membershipsViaUser ?? [], $membershipsViaUserGroup ?? []);
        $memberships[] = new FolderMembership($folder, $request->user); // TODO: don't add owner twice
        $folder->setMemberships($memberships);

        $this->em->persist($folder);
        $this->em->flush();

        return $folder;
    }

    public function remove(Request $request) {
        $folder = $this->getEntity($request);
        if (empty($folder)) {
            throw new UserException('Folder not found');
        }

        $this->em->remove($folder);
        $this->em->flush();

        return $folder;
    }

    private function getEntity(Request $request) {
        $id = $request->args->id;

        return $request->user->getPermissions()->getVisibleFoldersQueryBuilder($this->em)
            ->andWhere('folder.id = :id')->setParameter('id', $id)
            ->getQuery()->getOneOrNullResult();
    }

    private static function getFilteringRules() {
        $rules = [];

        return $rules;
    }

    private static function getSortingRules() {
        $rules = [
            'creationTimestamp' => function($qb, $direction) {
                return $qb->addOrderBy('folder.creationTimestamp', $direction);
            },
            'name' => function($qb, $direction) {
                return $qb->addOrderBy('folder.name', $direction);
            },
        ];

        return $rules;
    }
}
