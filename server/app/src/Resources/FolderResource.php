<?php

namespace App\Resources;

use App\Exceptions\IllegalArgumentException;
use App\Exceptions\UserException;
use App\Helpers\FilteringHelper;
use App\Helpers\PaginationHelper;
use App\Helpers\SortingHelper;
use App\Http\Request;
use App\Model\Entities\Folder;

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
            throw new \Exception('Name is required!');
        }

        $folder = new Folder($entity->name, $request->user);

        // create folderMembership w/ user(Group)
        // persist it & set it

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

        // create folderMembership w/ user(Group) if not exist
        // persist it

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
