<?php

namespace App\Http;

use App\Application;

class Router {

    const API_PREFIX = '/api/';

    const API_ENDPOINTS = [
        // ''                      => 'PlatformController',
        
        // 'session'               => 'SessionController',
        // 'sessions'              => 'SessionsController',

        'users'                 => 'UsersController',
        'users/{id}'            => 'UserController',

        'folders'               => 'FoldersController',
        'folders/{id}'          => 'FolderController',

        // 'blobs'                 => 'BlobsController',
        // 'blobs/{hash}'          => 'BlobController',
    ];

    public static function registerRouteCallbacks(Application $app) {
        foreach(self::API_ENDPOINTS as $route => $controller) {
            $app->any(self::API_PREFIX . $route, "App\\Controllers\\$controller");
        }
    }
}
