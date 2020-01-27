<?php

namespace App\Controllers;

use App\Model\Entities\Folder;
use App\Helpers\ResourceHelper;
use App\Http\Request;
use App\Resources\FolderResource;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Response;

class FolderController extends AbstractController {

    private $resource;

    public function __construct(ContainerInterface $container) {
        parent::__construct($container);
        $this->resource = new FolderResource($this->em);
    }

    protected function handleGet(Request $request, Response $response) {
        $folder = $this->resource->read($request);

        $ctx = (object)[
            'user' => $request->user
        ];
        $ret = self::asJson($folder, $request->fields, $ctx);
        $this->encodeResponseBody($response, $ret);
        return $response;
    }

    protected function handlePut(Request $request, Response $response) {
        $folder = $this->resource->update($request);

        $ctx = (object)[
            'user' => $request->user
        ];
        $ret = self::asJson($folder, $request->fields, $ctx);
        $this->encodeResponseBody($response, $ret);
        return $response;
    }

    protected function handleDelete(Request $request, Response $response) {
        $folder = $this->resource->remove($request);

        $ctx = (object)[
            'user' => $request->user
        ];
        $ret = self::asJson($folder, $request->fields, $ctx);
        $this->encodeResponseBody($response, $ret);
        return $response;
    }

    public static function asJson(Folder $folder, $fields = null, $ctx = null) {
        $resourceMap = [];

        $resourceMap += [
            'id'                => fn($folder) => $folder->getId(),
            'name'              => fn($folder) => $folder->getName(),
            'creator'           => fn($folder) => UserController::asJson($folder->getCreator(), null, $ctx),
            'members'           => fn($folder) => array_map(fn($user) => $user->getId(), $folder->getMembers()),
            'creationTimestamp' => function($folder) use ($ctx) { return $folder->getCreationTimestamp()->format('Y-m-d H:i:s'); },
        ];

        return ResourceHelper::mapValues($folder, $resourceMap, $fields);
    }
}
