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

        'userGroups'            => 'UserGroupsController',
        'userGroups/{id}'       => 'UserGroupController',

        'folders'               => 'FoldersController',
        'folders/{id}'          => 'FolderController',

        // 'blobs'                 => 'BlobsController',
        // 'blobs/{hash}'          => 'BlobController',
    ];

    const CLIENT_ENDPOINTS = [
        '/auth'                  => '/auth/index.php',
        '/*'                  => '/404/index.php',
    ];

    public static function registerRouteCallbacks(Application $app) {
        foreach(self::API_ENDPOINTS as $route => $controller) {
            $app->any(self::API_PREFIX . $route, "App\\Controllers\\$controller");
        }

        $publicDir = $app->getContainer()->get('config')->publicDir;

        foreach(self::CLIENT_ENDPOINTS as $route => $path) {
            $path = $publicDir . $path;

            $app->get($route, function($request, $response, $args) use ($path) {
                $response->getBody()->write(file_get_contents($path));
                return $response;
            });
        }
    }
}
