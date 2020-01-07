<?php

namespace App\Filters;

use App\Application;

class FilterChain {

    public static function registerMiddleware(Application $app) {
        $container = $app->container;

        $filters = [
            new CorsFilter($container),
            new RequestBodyParserFilter($container),
            new SessionFilter($container),
            new AuthFilter($container),
        ];

        foreach($filters as $filter) {
            $app->add($filter);
        }
    }
}
