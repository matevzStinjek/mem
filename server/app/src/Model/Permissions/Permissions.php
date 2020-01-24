<?php

namespace App\Model\Permissions;

use App\Model\Entities\RegisteredUser;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

abstract class Permissions {

    protected function defaultPermission($functionName, array $args, $readOnly) {
        return false;
    }

    /**
     * READ PERMISSIONS
     */

    public function canReadUserDetails(RegisteredUser $user) { return $this->defaultPermission(__FUNCTION__, func_get_args(), true); }

    /**
     * CREATE PERMISSIONS
     */

    public function canCreateNewUser() { return $this->defaultPermission(__FUNCTION__, func_get_args(), true); }
    public function canCreateNewUserGroup() { return $this->defaultPermission(__FUNCTION__, func_get_args(), true); }
    public function canCreateNewFolder() { return $this->defaultPermission(__FUNCTION__, func_get_args(), true); }
    public function canCreateNewSession() { return $this->defaultPermission(__FUNCTION__, func_get_args(), true); }

    /**
     * VISIBLE QUERY BUILDERS
     */

    protected function defaultAddQueryBuilderConditions($queryName, QueryBuilder $qb) {
    }

    final public function getVisibleRegisteredUsersQueryBuilder(EntityManager $em) {
        $qb = $em->createQueryBuilder()
            ->select('registeredUser')
            ->from('App\Model\Entities\RegisteredUser', 'registeredUser');

        $this->addVisibleRegisteredUsersQueryBuilderConditions($qb);
        return $qb;
    }

    protected function addVisibleRegisteredUsersQueryBuilderConditions(QueryBuilder $qb) { $this->defaultAddQueryBuilderConditions(__FUNCTION__, $qb); }

    final public function getVisibleUserGroupsQueryBuilder(EntityManager $em) {
        $qb = $em->createQueryBuilder()
            ->select('userGroup')
            ->from('App\Model\Entities\UserGroup', 'userGroup');

        $this->addVisibleUserGroupsQueryBuilderConditions($qb);
        return $qb;
    }

    protected function addVisibleUserGroupsQueryBuilderConditions(QueryBuilder $qb) { $this->defaultAddQueryBuilderConditions(__FUNCTION__, $qb); }

    final public function getVisibleFoldersQueryBuilder(EntityManager $em) {
        $qb = $em->createQueryBuilder()
            ->select('folder')
            ->from('App\Model\Entities\Folder', 'folder');

        $this->addVisibleRegisteredUsersQueryBuilderConditions($qb);
        return $qb;
    }

    protected function addVisibleFoldersQueryBuilderConditions(QueryBuilder $qb) { $this->defaultAddQueryBuilderConditions(__FUNCTION__, $qb); }

    /**
     * SEARCHABLE QUERY BUILDERS
     */

    final public function getSearchableRegisteredUsersQueryBuilder(EntityManager $em) {
        $qb = $em->createQueryBuilder()
            ->select('registeredUser')
            ->from('App\Model\Entities\RegisteredUser', 'registeredUser');

        $this->addSearchableRegisteredUsersQueryBuilderConditions($qb);
        return $qb;
    }

    protected function addSearchableRegisteredUsersQueryBuilderConditions(QueryBuilder $qb) { $this->defaultAddQueryBuilderConditions(__FUNCTION__, $qb); }

    final public function getSearchableUserGroupsQueryBuilder(EntityManager $em) {
        $qb = $em->createQueryBuilder()
            ->select('userGroup')
            ->from('App\Model\Entities\UserGroup', 'userGroup');

        $this->addSearchableUserGroupsQueryBuilderConditions($qb);
        return $qb;
    }

    protected function addSearchableUserGroupsQueryBuilderConditions(QueryBuilder $qb) { $this->defaultAddQueryBuilderConditions(__FUNCTION__, $qb); }

    final public function getSearchableFoldersQueryBuilder(EntityManager $em) {
        $qb = $em->createQueryBuilder()
            ->select('folder')
            ->from('App\Model\Entities\Folder', 'folder');

        $this->addSearchableFoldersQueryBuilderConditions($qb);
        return $qb;
    }

    protected function addSearchableFoldersQueryBuilderConditions(QueryBuilder $qb) { $this->defaultAddQueryBuilderConditions(__FUNCTION__, $qb); }

    public function __toString() {
        return get_called_class();
    }
}
