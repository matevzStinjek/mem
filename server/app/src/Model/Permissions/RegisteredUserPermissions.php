<?php

namespace App\Model\Permissions;

use App\Model\Entities\RegisteredUser;

class RegisteredUserPermissions extends Permissions {

    protected $user;

    public function __construct(RegisteredUser $user) {
        $this->user = $user;
    }
}
