<?php

namespace App\Http;

use App\Application;

class Router {

    const API_PREFIX = '/api/';

    const API_ENDPOINTS = [
        ''                     => 'ExampleController',
        // ''                     => 'PlatformController',

        // 'users'                 => 'UserController',
        // 'users/{id}'            => 'UserController',

        // 'folders'               => 'FolderController',
        // 'folders/{id}'          => 'FolderController',

        // 'blobs'                 => 'BlobController',
        // 'blobs/{hash}'          => 'BlobController',
    ];

    public static function registerRouteCallbacks(Application $app) {
        $app->group(self::API_PREFIX, function() use($app) {
            foreach(self::API_ENDPOINTS as $route => $controller) {
                $app->any(self::API_PREFIX . $route, "App\\Controllers\\$controller");
            }
        });
    }
}
