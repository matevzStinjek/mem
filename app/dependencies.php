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

// S3
$container['s3'] = function ($c) {
    $settings = $c->get('settings');
    $connection = $settings['aws']['s3']['connection'];
    $s3Client = new Aws\S3\S3Client($connection);
    return $s3Client;
};

// -----------------------------------------------------------------------------
// Repository factories
// -----------------------------------------------------------------------------

$container['App\Controllers\BlobController'] = function ($c) {
    $settings = $c->get('settings');
    $bucket = $settings['aws']['s3']['meta']['bucket'];
    $assetRepository = new \App\Repositories\AssetRepository($c->get('s3'), $bucket);
    return new App\Controllers\BlobController($assetRepository);
};

// -----------------------------------------------------------------------------
// Resource factories
// -----------------------------------------------------------------------------

$container['App\Controllers\UserController'] = function ($c) {
    $userResource = new \App\Resource\UserResource($c->get('em'));
    return new App\Controllers\UserController($userResource);
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
