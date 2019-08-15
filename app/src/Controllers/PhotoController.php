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

    function get(Request $request, Response $response, array $args) {
        $response->withJson('get');
        return $response;
    }

    function getAll(Request $request, Response $response, array $args) {
        $photos = $this->photoResource->getAll();
        error_log(json_encode($photos));

        $response->withJson($photos);
        return $response;
    }

    function post(Request $request, Response $response, array $args) {
        $response->withJson('post');
        return $response;
    }

    function update(Request $request, Response $response, array $args) {
        $response->withJson('update');
        return $response;
    }

    function delete(Request $request, Response $response, array $args) {
        $response->withJson('delete');
        return $response;
    }
}
