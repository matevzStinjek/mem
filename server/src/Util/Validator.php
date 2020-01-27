<?php

namespace App\Util;

use App\Exceptions\IllegalArgumentException;

class Validator {

    public static function name($name) {
        if (!is_string($name)) {
            throw new IllegalArgumentException('Name must be a string.');
        }

        $name = trim($name);
        $name = \Normalizer::normalize($name);

        if (!strlen($name)) {
            throw new IllegalArgumentException('Name cannot be empty.');
        }

        if (strlen($name) > 255) {
            throw new IllegalArgumentException('Name cannot be longer than 255 characters.');
        }
    }

    public static function email($email) {
        if (!is_string($email)) {
            throw new IllegalArgumentException('Email must be a string.');
        }

        $email = trim($email);

        if (!preg_match('/^.+@.+\..{2,}$/', $email)) {
            throw new IllegalArgumentException('Invalid e-mail format.');
        }

        if (strlen($email) > 255) {
            throw new IllegalArgumentException('Email cannot be longer than 255 characters.');
        }
    }

    public static function password($password) {
        if (!is_string($password)) {
            throw new IllegalArgumentException('Password must be a string.');
        }

        $password = trim($password);
        $password = \Normalizer::normalize($password);

        if (strlen($password) < 6) {
            throw new IllegalArgumentException('Password cannot be shorter than 6 characters.');
        }
        if (strlen($password) > 16) {
            throw new IllegalArgumentException('Password cannot be longer than 16 characters.');
        }
        if (!preg_match('@[A-Z]@', $password)) {
            throw new IllegalArgumentException('Password must contain at least one uppercase character');
        }
        if (!preg_match('@[a-z]@', $password)) {
            throw new IllegalArgumentException('Password must contain at least one lowercase character');
        }
        if (!preg_match('@[0-9]@', $password)) {
            throw new IllegalArgumentException('Password must contain at least one number');
        }
        if (!preg_match('@[^\w]@', $password)) {
            throw new IllegalArgumentException('Password must contain at least one special character');
        }
    }
}
