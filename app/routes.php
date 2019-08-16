<?php

const API_PREFIX = '/api';

// =============================
// format: $route => $controller
// =============================

$routes = [
    '/photos'           => 'PhotoController',
    '/photos/{id}'      => 'PhotoController',
    '/assets'           => 'AssetController',
];

foreach($routes as $route => $controller) {
    $app->any(API_PREFIX . $route, "App\\Controllers\\$controller:handleRequest");
}
