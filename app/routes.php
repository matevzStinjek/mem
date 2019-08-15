<?php

const API_PREFIX = '/api';

// =============================
// format: $route => $controller
// =============================

$routes = [
    '/photo'            => 'PhotoController',
    '/photo/{id}'       => 'PhotoController',
];

foreach($routes as $route => $controller) {
    $app->any(API_PREFIX . $route, "App\\Controllers\\$controller:handleRequest");
}
