<?php

namespace App\Model\ORM;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class EntityManagerFactory {

    public static function create($doctrineConfig) {
        self::setCustomDoctrineDataTypes();

        $config = Setup::createAnnotationMetadataConfiguration(
            $doctrineConfig['meta']['entity_path'],
            $doctrineConfig['meta']['auto_generate_proxies'],
            $doctrineConfig['meta']['proxy_dir'],
            $doctrineConfig['meta']['cache'],
            $doctrineConfig['meta']['simple_annotation_reader'],
        );

        return EntityManager::create($doctrineConfig['connection'], $config);
    }

    private static function setCustomDoctrineDataTypes() {
        Type::overrideType('json', 'App\Model\ORM\JsonType');
        Type::addType('set',  'App\Model\ORM\SetType');
    }
}