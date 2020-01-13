<?php

namespace App\Controllers;

use App\Exceptions\UserException;
use App\Http\Request;
use App\Resources\FolderResource;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Response;

class FoldersController extends AbstractController {

    private $resource;

    public function __construct(ContainerInterface $container) {
        parent::__construct($container);
        $this->resource = new FolderResource($this->em);
    }

    protected function handleGet(Request $request, Response $response) {
        $folders = $this->resource->readAll($request);

        $ctx = (object)[
            'user' => $request->user
        ];

        $foldersAsJson = [];
        foreach ($folders as $folder) {
            $foldersAsJson[] = FolderController::asJson($folder, $request->fields, $ctx);
        }

        $this->encodeResponseBody($response, $foldersAsJson);
        return $response;
    }

    protected function handlePost(Request $request, Response $response) {
        if (!$request->user->getPermissions()->canCreateNewFolder())
            throw new UserException('You do not have the required permissions to create a new user.');

        $folder = $this->resource->create($request);

        $ctx = (object)[
            'user' => $request->user
        ];
        $ret = FolderController::asJson($folder, $request->fields, $ctx);
        $this->encodeResponseBody($response, $ret);
        return $response;
    }
}
