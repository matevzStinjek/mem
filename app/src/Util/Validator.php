<?php

namespace App\Util;

class Validator {

    public static function email($email) {
        if (!is_string($email)) {
            throw new \Exception('Email must be a string.');
        }

        if (!preg_match('/^.+@.+\..{2,}$/', $email)) {
            throw new \Exception('Invalid e-mail format.');
        }
    }
}
