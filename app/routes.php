<?php

// =============================
// format: $route => $controller
// =============================
$routes = [
    '/photo'            => 'PhotoController',
    '/photo/{id}'       => 'PhotoController',
];

foreach($routes as $route => $controller) {
    $app->any("/api$route","App\\Controllers\\$controller:handleRequest");
}
