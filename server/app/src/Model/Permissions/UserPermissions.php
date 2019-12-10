<?php

namespace App\Model\Permissions;

use App\Model\Entites\User;

class UserPermissions extends Permissions {

    private $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function __toString() {
        return 'UserPermissions';
    }
}
