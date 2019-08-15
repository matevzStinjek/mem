<?php

$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// Doctrine
$container['em'] = function ($c) {
    $settings = $c->get('settings');
    $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
        $settings['doctrine']['meta']['entity_path'],
        $settings['doctrine']['meta']['auto_generate_proxies'],
        $settings['doctrine']['meta']['proxy_dir'],
        $settings['doctrine']['meta']['cache'],
        false
    );
    return \Doctrine\ORM\EntityManager::create($settings['doctrine']['connection'], $config);
};

// -----------------------------------------------------------------------------
// Resource factories
// -----------------------------------------------------------------------------

$container['App\Controllers\PhotoController'] = function ($c) {
    $photoResource = new \App\Resource\PhotoResource($c->get('em'));
    return new App\Controllers\PhotoController($photoResource);
};
