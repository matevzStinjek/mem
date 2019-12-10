<?php

namespace App\Model\Permissions;

class Roles {

    const ROLE_ADMIN  = 'ADMIN';
    const ROLE_SAURON = 'SAURON';
    const ROLE_USER   = 'USER';
    const ROLE_DEMO   = 'DEMO';

    public static function getImpliedRoles($role) {
        switch ($role) {
            case Roles::ROLE_ADMIN:
                return [Roles::ROLE_SAURON];
            default:
                return [];
        }
    }

    public static function getExistingRoles() {
        return (new ReflectionClass(__CLASS__))->getConstants();
    }
}
