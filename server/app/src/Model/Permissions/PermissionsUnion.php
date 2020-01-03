<?php

namespace App\Model\Permissions;

class PermissionsUnion extends Permissions {

    private $permissions = [];

    public function __construct(array $permissions) {
        foreach ($permissions as $permission) {
            if (!in_array($permission, $this->permissions)) {
                $this->permissions[] = $permission;
            }
        }
    }

    protected function defaultPermission($functionName, array $args, $isReadOnly) {
        foreach ($this->permissions as $permission) {
            if (call_user_func_array([$permission, $functionName], $args))
                return true;
        }
        return false;
    }
}
