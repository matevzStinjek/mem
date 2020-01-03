<?php

namespace App\Model\Entities;

use App\Model\Permissions\PublicPermissions;

class UnregisteredUser implements User {

    private $permissionsCache;

    public function getPermissions() {
        if (empty($this->permissionsCache)) {
            $this->permissionsCache = new PublicPermissions;
        }

        return $this->permissionsCache;
    }
}
