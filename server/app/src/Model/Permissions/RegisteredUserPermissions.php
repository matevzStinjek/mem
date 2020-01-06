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
