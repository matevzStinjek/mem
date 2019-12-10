<?php

namespace App\Util;

class Util {

    public static function hashPassword($password, $salt) {
        return hash('sha256', $password . $salt);
    }
}