<?php

namespace App\Filters;

use App\Application;

class FilterChain {

    public static function registerMiddleware(Application $app) {
        $container = $app->container;

        $filters = [
            new CorsFilter($container),
            // new SessionFilter($container),
            new AuthFilter($container),
            new RequestBodyParserFilter($container)
        ];

        foreach($filters as $filter) {
            $app->add($filter);
        }
    }
}
