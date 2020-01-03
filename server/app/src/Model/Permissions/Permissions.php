<?php

namespace App\Model\Permissions;

abstract class Permissions {

    public function canCreateNewUser() { return $this->defaultPermission(__FUNCTION__, func_get_args(), true); }

    protected function defaultPermission($functionName, array $args, $readOnly) {
        return false;
    }
}
