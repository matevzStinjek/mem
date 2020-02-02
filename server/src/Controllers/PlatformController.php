<?php

namespace App\Controllers;

use App\Http\Request;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Response;

class PlatformController extends AbstractController {

    public function getAll(Request $request, Response $response) {
        $response->getBody()->write('Hello World');
        return $response;
    }
}
