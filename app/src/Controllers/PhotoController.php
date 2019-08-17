<?php

namespace App\Controllers;

use App\Entity\Photo;
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
        $json = self::asJson($photo);
        $response->withJson($json);
        return $response;
    }

    protected function getAll(Request $request, Response $response, array $args) {
        $photos = $this->photoResource->readAll();
        $json = array_map(function($photo) { return self::asJson($photo); }, $photos);
        $response->withJson($json);
        return $response;
    }

    protected function post(Request $request, Response $response, array $args) {
        $entity = $this->decodeRequestBody($request);
        $photo = $this->photoResource->create($entity);
        $json = self::asJson($photo);
        $response->withJson($json);
        return $response;
    }

    protected function put(Request $request, Response $response, array $args) {
        $entity = $this->decodeRequestBody($request);
        $photo = $this->photoResource->update($entity);
        $json = self::asJson($photo);
        $response->withJson($json);
        return $response;
    }

    protected function delete(Request $request, Response $response, array $args) {
        $entity = $this->decodeRequestBody($request);
        $photo = $this->photoResource->remove($entity);
        $json = self::asJson($photo);
        $response->withJson($json);
        return $response;
    }

    public static function asJson(Photo $photo) {
        $resourceMap = [];

        $resourceMap += [
            'id'     => function($photo) { return $photo->getId(); },
            'title'  => function($photo) { return $photo->getTitle(); },
            'slug'   => function($photo) { return $photo->getSlug(); },
            'image'  => function($photo) { return $photo->getImage(); },
            'parent' => function($photo) { return $photo->getParent() ? PhotoController::asJson($photo->getParent()) : null; },
        ];

        $ret = [];
        foreach($resourceMap as $resource) {
            $ret[] = $resource($photo);
        }

        return $ret;
    }
}
