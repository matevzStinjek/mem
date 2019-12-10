<?php

namespace Celtra\Model\Permissions;

class PermissionsUnion extends Permissions {

    private $permissions = [];

    public function __construct($permissions) {
        foreach ($permissions as $permission) {
            if (!in_array($permission, $this->permissions)) {
                $this->permissions[] = $permission;
            }
        }
    }

    public function __toString()
    {
        return "PermissionsUnion(" . implode(', ', $this->permissions) . ")";
    }

    protected function defaultPermission($functionName, array $args, $readOnly)
    {
        foreach ($this->permissions as $permission) {;
            if (call_user_func_array([$permission, $functionName], $args)) {
                return true;
            }
        }
        return false;
    }
}
