<?php

namespace App\Model\Permissions;

use App\Model\Entities\RegisteredUser;

class UserPermissions extends Permissions {

    private $user;

    public function __construct(RegisteredUser $user) {
        $this->user = $user;
    }
}
