<?php

namespace App\Model\Permissions;

use App\Model\Entities\RegisteredUser;

class AdminPermissions extends Permissions {

    /**
     * READ Permissions
     */

    public function canReadUserDetails(RegisteredUser $user) {
        return true;
    }

    /**
     * CREATE Permissions
     */

    public function canCreateNewUFolder() {
        return true;
    }
}
