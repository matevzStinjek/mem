<?php

namespace App\Filters;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

interface Filter {

    // Middleware function
    public function __invoke(Request $request, RequestHandler $handler): Response;
}
