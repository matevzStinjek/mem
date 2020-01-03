<?php

namespace App\Model\Entities;

interface User {

    /**
     * @return Permissions
     */
    public function getPermissions();
}
