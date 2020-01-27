<?php

namespace App\Controllers;

use App\Model\Entities\UserGroup;
use App\Helpers\ResourceHelper;
use App\Http\Request;
use App\Resources\UserGroupResource;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Response;

class UserController extends AbstractController {

    private $resource;

    public function __construct(ContainerInterface $container) {
        parent::__construct($container);
        $this->resource = new UserGroupResource($this->em);
    }

    protected function handleGet(Request $request, Response $response) {
        $userGroup = $this->resource->read($request);

        $ctx = (object)[
            'user' => $request->user
        ];
        $ret = self::asJson($userGroup, $request->fields, $ctx);
        $this->encodeResponseBody($response, $ret);
        return $response;
    }

    protected function handlePut(Request $request, Response $response) {
        $userGroup = $this->resource->update($request);

        $ctx = (object)[
            'user' => $request->user
        ];
        $ret = self::asJson($userGroup, $request->fields, $ctx);
        $this->encodeResponseBody($response, $ret);
        return $response;
    }

    protected function handleDelete(Request $request, Response $response) {
        $userGroup = $this->resource->remove($request);

        $ctx = (object)[
            'user' => $request->user
        ];
        $ret = self::asJson($userGroup, $request->fields, $ctx);
        $this->encodeResponseBody($response, $ret);
        return $response;
    }

    public static function asJson(UserGroup $userGroup, $fields = null, $ctx = null) {
        $resourceMap = [];

        $resourceMap += [
            'id'                => function($userGroup) { return $userGroup->getId(); },
            'name'              => function($userGroup) { return $userGroup->getName(); },
            'users'             => function($userGroup) { return array_map(fn($user) => $user->getId(), $userGroup->getUsers()); },
            'creationTimestamp' => function($userGroup) { return $userGroup->getCreationTimestamp()->format('Y-m-d H:i:s'); },
        ];

        return ResourceHelper::mapValues($userGroup, $resourceMap, $fields);
    }
}
