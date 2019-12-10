<?php

namespace App\Model\ORM;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class JsonType extends Type {

    const TYPE_NAME = 'json';

    public function getName() {
        return self::TYPE_NAME;
    }
    
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform) {
        return 'LONGBLOB';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform) {
        if ($value === null) {
            return null;
        }

        return gzencode(json_encode($value));
    }

    public function convertToPHPValue($value, AbstractPlatform $platform) {
        if ($value === null) {
            return null;
        }

        return json_decode(gzdecode($value));
    }
}
