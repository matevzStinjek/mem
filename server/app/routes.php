<?php

// =============================
// format: $route => $controller
// =============================

$routes = [
    '/users'                => 'UserController',
    '/users/{id}'           => 'UserController',

    '/folders'              => 'FolderController',
    '/folders/{id}'         => 'FolderController',

    '/blobs'                => 'BlobController',
    '/blobs/{hash}'         => 'BlobController',
];

$app->group('/api', function() use($app, $routes) {
    foreach($routes as $route => $controller) {
        $app->any($route, "App\\Controllers\\$controller:handleRequest");
    }
});
