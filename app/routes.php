<?php

const API_PREFIX = '/api';

// =============================
// format: $route => $controller
// =============================

$routes = [
    // (temporary) TODO: delete 
    '/photos'               => 'PhotoController',
    '/photos/{id}'          => 'PhotoController',

    '/users'                => 'UserController',
    '/users/{id}'           => 'UserController',

    // '/folders'              => 'FolderController',
    // '/folders/{id}'         => 'FolderController',

    // '/folderContent'        => 'FolderController',
    // '/folderContent/{id}'   => 'FolderController',

    '/blobs'                => 'BlobController',
    '/blobs/{hash}'         => 'BlobController',
];

foreach($routes as $route => $controller) {
    $app->any(API_PREFIX . $route, "App\\Controllers\\$controller:handleRequest");
}
