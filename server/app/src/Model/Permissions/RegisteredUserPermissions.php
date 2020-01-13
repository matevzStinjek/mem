<?php

namespace App\Model\Permissions;

use App\Model\Entities\RegisteredUser;
use Doctrine\ORM\QueryBuilder;

class RegisteredUserPermissions extends Permissions {

    protected $user;

    public function __construct(RegisteredUser $user) {
        $this->user = $user;
    }

    /**
     * READ PERMISSIONS
     */

    public function canReadUserDetails(RegisteredUser $user) {
        return $this->user->getId() === $user->getId();
    }

    /**
     * CREATE PERMISSIONS
     */

    public function canCreateNewFolder() {
        return true;
    }

    /**
     * VISIBLE QUERY BUILDERS
     */

    protected function addVisibleRegisteredUsersQueryBuilderConditions(QueryBuilder $qb) {
        $qb->andWhere('1=1');
    }

    protected function addVisibleFoldersQueryBuilderConditions(QueryBuilder $qb) {
        $this->addCommonFoldersQueryBuilderConditions($qb);
    }

    /**
     * SEARCHABLE QUERY BUILDERS
     */

    protected function addSearchableRegisteredUsersQueryBuilderConditions(QueryBuilder $qb) {
        $qb->andWhere('1=1');
    }

    protected function addSearchableFoldersQueryBuilderConditions(QueryBuilder $qb) {
        $this->addCommonFoldersQueryBuilderConditions($qb);
    }

    /**
     * COMMON QUERY BUILDERS
     */

    private function addCommonFoldersQueryBuilderConditions(QueryBuilder $qb) {
        // add check for folders you're a part of, oh shit
        $qb->andWhere('1=1'); // add svasta
        /**
         * Join user <> userGroups <> folderMemberships <> folders
         * Join user <> folderMemberships <> folders
         * Join both
         * Unique
         */
    }
}
