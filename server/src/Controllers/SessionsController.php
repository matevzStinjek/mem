<?php

namespace App\Controllers;

use App\Exceptions\UserException;
use App\Http\Request;
use App\Resources\SessionResource;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Response;

class SessionsController extends AbstractController {

    private $resource;

    public function __construct(ContainerInterface $container) {
        parent::__construct($container);
        $this->resource = new SessionResource($this->em);
    }

    protected function handlePost(Request $request, Response $response) {
        if (!$request->user->getPermissions()->canCreateNewSession())
            throw new UserException('You do not have the required permissions to login.');

        $session = $this->resource->create($request);
        $ret = SessionController::asJson($session);
        $this->encodeResponseBody($response, $ret);
        return $response;
    }
}
