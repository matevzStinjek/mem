<?php

namespace App\Controllers;

use App\Entity\Folder;
use App\Resource\FolderResource;
use Slim\Http\Request;
use Slim\Http\Response;

class FolderController extends AbstractController {

    private $folderResource;

    public function __construct(FolderResource $folderResource) {
        $this->folderResource = $folderResource;
    }
    
    protected function get(Request $request, Response $response, array $args) {
        $folder = $this->folderResource->read($args['id']);
        $json = self::asJson($folder);
        return $response->withJson($json);
    }

    protected function getAll(Request $request, Response $response, array $args) {
        $folders = $this->folderResource->readAll();
        $json = array_map(function($folder) { return self::asJson($folder); }, $folders);
        return $response->withJson($json);
    }

    protected function post(Request $request, Response $response, array $args) {
        $entity = $this->decodeRequestBody($request);
        $id = $this->folderResource->create($entity);
        return $response->write($id);
    }

    protected function put(Request $request, Response $response, array $args) {
        $entity = $this->decodeRequestBody($request);
        $id = $this->folderResource->update($args['id'], $entity);
        return $response->write($id);
    }

    protected function delete(Request $request, Response $response, array $args) {
        $id = $this->folderResource->remove($args['id']);
        return $response->write($id);
    }

    public static function asJson(Folder $folder) {
        $resourceMap = [];

        $resourceMap += [
            'id'                => function($folder) { return $folder->getId(); },
            'name'              => function($folder) { return $folder->getName(); },
            'creator'           => function($folder) { return UserController::asJson($folder->getCreator()); },
            'creationTimestamp' => function($folder) { return $folder->getCreationTimestamp()->format('Y-m-d H:i:s'); },
        ];

        $ret = [];
        foreach($resourceMap as $key => $mapper) {
            $ret[$key] = $mapper($folder);
        }
        return $ret;
    }

}
