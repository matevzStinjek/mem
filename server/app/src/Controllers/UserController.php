<?php

namespace App\Controllers;

use App\Model\Entities\RegisteredUser;
use App\Helpers\ResourceHelper;
use App\Http\Request;
use App\Resources\UserResource;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Response;

class UserController extends AbstractController {

    private $resource;

    public function __construct(ContainerInterface $container) {
        parent::__construct($container);
        $this->resource = new UserResource($this->em);
    }

    protected function handleGet(Request $request, Response $response) {
        $user = $this->resource->read($request);
        $ret = self::asJson($user, $request->fields);
        $this->encodeResponseBody($response, $ret);
        return $response;
    }

    protected function handlePut(Request $request, Response $response) {
        $user = $this->resource->update($request);
        $ret = self::asJson($user);
        $this->encodeResponseBody($response, $ret);
        return $response;
    }

    protected function handleDelete(Request $request, Response $response) {
        $user = $this->resource->remove($request);
        $ret = self::asJson($user);
        $this->encodeResponseBody($response, $ret);
        return $response;
    }

    public static function asJson(RegisteredUser $user, $fields = null) {
        $resourceMap = [];

        $resourceMap += [
            'id'                => function($user) { return $user->getId(); },
            'name'              => function($user) { return $user->getName(); },
            'email'             => function($user) { return $user->getEmail(); },
            'userGroupsIds'     => function($user) { return array_map(fn($userGroup) => $userGroup->getId(), $user->getUserGroups()->toArray()); },
            'creationTimestamp' => function($user) { return $user->getCreationTimestamp()->format('Y-m-d H:i:s'); },
        ];

        return ResourceHelper::mapValues($user, $resourceMap, $fields);
    }
}
