<?php

namespace App\Model\Permissions;

class PublicPermissions extends Permissions {

    public function canCreateNewUser() {
        return true;
    }
}
