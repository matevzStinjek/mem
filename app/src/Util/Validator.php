<?php

namespace App\Util;

class Validator {

    public static function emailFormat($email) {
        if (!is_string($email)) {
            throw new \Exception('Email must be a string.');
        }

        if (!preg_match('/^.+@.+\..{2,}$/', $email)) {
            throw new \Exception('Invalid e-mail format.');
        }
    }

    public static function name($name) {
        if (!is_string($name)) {
            throw new \Exception('Name must be a string.');
        }

        $name = trim($name);
        $name = \Normalizer::normalize($name);

        if (!strlen($name)) {
            throw new \Exception('Name cannot be empty.');
        }

        if (strlen($name) > 255) {
            throw new \Exception('Name cannot be longer than 255 characters.');
        }
    }

    public static function email($email) {
        if (!is_string($email)) {
            throw new \Exception('Email must be a string.');
        }

        $email = trim($email);

        try {
            self::emailFormat($email);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        if (strlen($email) > 255) {
            throw new \Exception('Email cannot be longer than 255 characters.');
        }
    }
}
