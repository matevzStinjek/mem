<?php

namespace App\Model\Permissions;

class PublicPermissions extends Permissions {

    public function canCreateNewUser() {
        return true;
    }

    public function canCreateNewSession() {
        return true;
    }
}
