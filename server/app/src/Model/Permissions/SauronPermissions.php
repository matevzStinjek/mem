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

    /**
     * VISIBLE QUERY BUILDERS
     */

    protected function addSearchableRegisteredUsersQueryBuilderConditions(QueryBuilder $qb) {
        $qb->andWhere('1=1');
    }
}
