<?php

namespace App\Model\Permissions;

class AdminPermissions extends Permissions {

    public function __toString() {
        return 'AdminPermissions';
    }

    public function canTest() {
        return false;
    }
}
