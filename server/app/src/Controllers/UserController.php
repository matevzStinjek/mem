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

        $ctx = (object)[
            'user' => $request->user
        ];
        $ret = self::asJson($user, $request->fields, $ctx);
        $this->encodeResponseBody($response, $ret);
        return $response;
    }

    protected function handlePut(Request $request, Response $response) {
        $user = $this->resource->update($request);

        $ctx = (object)[
            'user' => $request->user
        ];
        $ret = self::asJson($user, $request->fields, $ctx);
        $this->encodeResponseBody($response, $ret);
        return $response;
    }

    protected function handleDelete(Request $request, Response $response) {
        $user = $this->resource->remove($request);

        $ctx = (object)[
            'user' => $request->user
        ];
        $ret = self::asJson($user, $request->fields, $ctx);
        $this->encodeResponseBody($response, $ret);
        return $response;
    }

    public static function asJson(RegisteredUser $user, $fields = null, $ctx = null) {
        $resourceMap = [];

        $resourceMap += [
            'id'    => function($user) { return $user->getId(); },
            'name'  => function($user) { return $user->getName(); },
            'email' => function($user) { return $user->getEmail(); },
        ];

        if ($ctx->user->getPermissions()->canReadUserDetails($user)) {
            $resourceMap += [
                'userGroupsIds'     => function($user) { return array_map(fn($userGroup) => $userGroup->getId(), $user->getUserGroups()); },
                'folderIds'         => function($user) { return array_map(fn($folder) => $folder->getId(), $user->getFolders()); },
                'creationTimestamp' => function($user) { return $user->getCreationTimestamp()->format('Y-m-d H:i:s'); },
            ];
        }

        return ResourceHelper::mapValues($user, $resourceMap, $fields);
    }
}
