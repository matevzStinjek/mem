<?php

namespace App\Controllers;

use App\Exceptions\UserException;
use App\Http\Request;
use App\Resources\UserResource;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Response;

class UsersController extends AbstractController {

    private $resource;

    public function __construct(ContainerInterface $container) {
        parent::__construct($container);
        $this->resource = new UserResource($this->em);
    }

    protected function handleGet(Request $request, Response $response) {
        $this->encodeResponseBody($response, 'get users');
        return $response;
    }

    protected function handlePost(Request $request, Response $response) {
        if (!$request->user->getPermissions()->canCreateNewUser())
            throw new UserException('You do not have the required permissions to create a new user.');

        $user = $this->resource->create($request);
        $ret = UserController::asJson($user);
        $this->encodeResponseBody($response, $ret);
        return $response;
    }
}
