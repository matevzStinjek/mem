<?php

namespace App\Controllers;

use App\Http\Request;
use App\Resources\ExampleResource;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Response;

class ExampleController extends AbstractController {

    private $resource;

    public function __construct(ContainerInterface $container) {
        parent::__construct($container);
        $this->resource = new ExampleResource($this->em);
    }

    public function getAll(Request $request, Response $response) {
        $example = $this->resource->read();
        
        $response->getBody()->write(
            json_encode($example->getJsn())
        );

        return $response;
    }

    public function post(Request $request, Response $response) {
        $id = $this->resource->create();
        $response->getBody()->write(json_encode($id));
        return $response;
    }
}
