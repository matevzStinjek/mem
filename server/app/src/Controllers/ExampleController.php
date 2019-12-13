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

    protected function handleGet(Request $request, Response $response) {
        $example = $this->resource->read();
        $this->encodeResponseBody($response, '$example');
        return $response;
    }

    protected function handlePost(Request $request, Response $response) {
        $id = $this->resource->create();
        $this->encodeResponseBody($response, $id);
        return $response;
    }
}
