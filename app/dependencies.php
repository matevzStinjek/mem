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
// Error handling
// -----------------------------------------------------------------------------

$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        return $response->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write('API Exception: ')
            ->write($exception->getMessage());
    };
};

// -----------------------------------------------------------------------------
// Resource factories
// -----------------------------------------------------------------------------

$container['App\Controllers\PhotoController'] = function ($c) {
    $photoResource = new \App\Resource\PhotoResource($c->get('em'));
    return new App\Controllers\PhotoController($photoResource);
};
