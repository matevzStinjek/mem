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
        $photo = $this->photoResource->read($args['id']);
        $response->withJson($photo);
        return $response;
    }

    protected function getAll(Request $request, Response $response, array $args) {
        $photos = $this->photoResource->readAll();
        $response->withJson($photos);
        return $response;
    }

    protected function post(Request $request, Response $response, array $args) {
        $entity = $this->decodeRequestBody($request);
        $this->photoResource->create($entity);
        $response->withJson('post');
        return $response;
    }

    protected function put(Request $request, Response $response, array $args) {
        $entity = $this->decodeRequestBody($request);
        $this->photoResource->update($entity);
        $response->withJson('put');
        return $response;
    }

    protected function delete(Request $request, Response $response, array $args) {
        $entity = $this->decodeRequestBody($request);
        $this->photoResource->remove($entity);
        $response->withJson('delete');
        return $response;
    }
}
