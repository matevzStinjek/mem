<?php

namespace App\Model;

interface User {

    /**
     * @return Permissions
     */
    public function getPermissions();
}
