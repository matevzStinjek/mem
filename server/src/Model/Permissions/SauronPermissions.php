<?php

namespace App\Model\Permissions;

use Doctrine\ORM\QueryBuilder;

/** The all-seeing eye */
class SauronPermissions extends Permissions {

    /**
     * VISIBLE QUERY BUILDERS
     */

    protected function addVisibleRegisteredUsersQueryBuilderConditions(QueryBuilder $qb) {
        $qb->andWhere('1=1');
    }

    protected function addVisibleUserGroupsQueryBuilderConditions(QueryBuilder $qb) {
        $qb->andWhere('1=1');
    }

    protected function addVisibleFoldersQueryBuilderConditions(QueryBuilder $qb) {
        $qb->andWhere('1=1');
    }

    /**
     * SEARCHABLE QUERY BUILDERS
     */

    protected function addSearchableRegisteredUsersQueryBuilderConditions(QueryBuilder $qb) {
        $qb->andWhere('1=1');
    }

    protected function addSearchableUserGroupsQueryBuilderConditions(QueryBuilder $qb) {
        $qb->andWhere('1=1');
    }

    protected function addSearchableFoldersQueryBuilderConditions(QueryBuilder $qb) {
        $qb->andWhere('1=1');
    }
}
