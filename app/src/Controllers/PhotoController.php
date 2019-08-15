<?php

namespace App\Controllers;

use App\Resource\PhotoResource;
use Slim\Http\Request;
use Slim\Http\Response;

final class PhotoController extends AbstractController {

    private $photoResource;

    public function __construct(PhotoResource $photoResource) {
        $this->photoResource = $photoResource;
    }

    protected function get(Request $request, Response $response, array $args) {
        $response->withJson('get');
        return $response;
    }

    protected function getAll(Request $request, Response $response, array $args) {
        $photos = $this->photoResource->getAll();
        $response->withJson($photos);
        return $response;
    }

    protected function post(Request $request, Response $response, array $args) {
        $response->withJson('post');
        return $response;
    }

    protected function update(Request $request, Response $response, array $args) {
        $response->withJson('update');
        return $response;
    }

    protected function delete(Request $request, Response $response, array $args) {
        $response->withJson('delete');
        return $response;
    }
}
