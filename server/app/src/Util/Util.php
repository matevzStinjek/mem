<?php

namespace App\Util;

class Util {

    public static function hashPassword($password) {
        return hash('sha512', $password);
    }
}