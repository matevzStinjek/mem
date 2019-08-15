<?php

$routes = [
    '/photo'            => 'PhotosController',
    '/photo/{id}'       => 'PhotoController',
];

foreach($routes as $route => $controller) {
    $app->any("/api$route","App\\Controllers\\$controller:handleRequest");
}