<?php

namespace App\Controllers;

use App\Exceptions\UserException;
use App\Http\Request;
use App\Resources\UserGroupResource;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Response;

class UsersController extends AbstractController {

    private $resource;

    public function __construct(ContainerInterface $container) {
        parent::__construct($container);
        $this->resource = new UserGroupResource($this->em);
    }

    protected function handleGet(Request $request, Response $response) {
        $userGroups = $this->resource->readAll($request);

        $ctx = (object)[
            'user' => $request->user
        ];

        $userGroupsAsJson = [];
        foreach ($userGroups as $userGroup) {
            $userGroupsAsJson[] = UserController::asJson($userGroup, $request->fields, $ctx);
        }

        $this->encodeResponseBody($response, $userGroupsAsJson);
        return $response;
    }

    protected function handlePost(Request $request, Response $response) {
        if (!$request->user->getPermissions()->canCreateNewUserGroup())
            throw new UserException('You do not have the required permissions to create a new user group.');

        $userGroup = $this->resource->create($request);

        $ctx = (object)[
            'user' => $request->user
        ];
        $ret = UserController::asJson($userGroup, $request->fields, $ctx);
        $this->encodeResponseBody($response, $ret);
        return $response;
    }
}
