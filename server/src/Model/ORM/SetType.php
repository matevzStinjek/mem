<?php

namespace App\Model\ORM;

use App\Exceptions\InvalidArgumentException;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class SetType extends Type {

    const TYPE_NAME = 'set';

    public function getName() {
        return self::TYPE_NAME;
    }

    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform) {
        return 'LONGBLOB';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform) {
        if ($value === null) {
            return null;
        }

        if (!empty($value)) {
            return explode(',', $value);
        }

        return [];
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform) {
        if ($value === null) {
            return null;
        }

        if (!is_array($value)) {
            throw new InvalidArgumentException('Invalid value: ' . gettype($value) . ' must be an array');
        }

        return implode(',', $value);
    }

}
