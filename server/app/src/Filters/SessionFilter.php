<?php

namespace App\Filters;

use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class SessionFilter implements Filter {

    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response {
        $response = $handler->handle($request);
        return $response;
    }
}
