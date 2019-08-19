<?php

const API_PREFIX = '/api';

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

foreach($routes as $route => $controller) {
    $app->any(API_PREFIX . $route, "App\\Controllers\\$controller:handleRequest");
}
