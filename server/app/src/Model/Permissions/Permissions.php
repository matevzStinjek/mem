<?php

namespace App\Model\Permissions;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

abstract class Permissions {

    /**
     * CREATE Permissions
     */

    protected function defaultPermission($functionName, array $args, $readOnly) {
        return false;
    }

    public function canCreateNewUser() { return $this->defaultPermission(__FUNCTION__, func_get_args(), true); }
    public function canCreateNewSession() { return $this->defaultPermission(__FUNCTION__, func_get_args(), true); }

    /**
     * VISIBLE QUERY BUILDERS
     */

    protected function defaultAddQueryBuilderConditions($queryName, QueryBuilder $qb) {}

    public function getVisibleRegisteredUsersQueryBuilder(EntityManager $em) {
        $qb = $em->createQueryBuilder()
            ->select('registeredUser')
            ->from('App\Model\Entities\RegisteredUser', 'registeredUser');

        $this->addVisibleRegisteredUsersQueryBuilderConditions($qb);
        return $qb;
    }

    protected function addVisibleRegisteredUsersQueryBuilderConditions(QueryBuilder $qb) { $this->defaultAddQueryBuilderConditions(__FUNCTION__, $qb); }

    /**
     * SEARCHABLE QUERY BUILDERS
     */

    public function getSearchableRegisteredUsersQueryBuilder(EntityManager $em) {
        $qb = $em->createQueryBuilder()
            ->select('registeredUser')
            ->from('App\Model\Entities\RegisteredUser', 'registeredUser');

        $this->addSearchableRegisteredUsersQueryBuilderConditions($qb);
        return $qb;
    }

    protected function addSearchableRegisteredUsersQueryBuilderConditions(QueryBuilder $qb) { $this->defaultAddQueryBuilderConditions(__FUNCTION__, $qb); }
}
