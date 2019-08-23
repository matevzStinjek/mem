<?php

namespace App\Controllers;

use App\Entity\User;
use App\Resource\UserResource;
use Slim\Http\Request;
use Slim\Http\Response;

final class UserController extends AbstractController {

    private $userResource;

    public function __construct(UserResource $userResource) {
        $this->userResource = $userResource;
    }

    protected function get(Request $request, Response $response, array $args) {
        $user = $this->userResource->read($args['id']);
        $json = self::asJson($user);
        return $response->withJson($json);
    }

    protected function getAll(Request $request, Response $response, array $args) {
        $params = $request->getParams();
        $users = $this->userResource->readAll($params);
        $json = array_map(function($user) { return self::asJson($user); }, $users);
        return $response->withJson($json);
    }

    protected function post(Request $request, Response $response, array $args) {
        $entity = $this->decodeRequestBody($request);
        $id = $this->userResource->create($entity);
        return $response->write($id);
    }

    protected function put(Request $request, Response $response, array $args) {
        $entity = $this->decodeRequestBody($request);
        $id = $this->userResource->update($args['id'], $entity);
        return $response->write($id);
    }

    protected function delete(Request $request, Response $response, array $args) {
        $id = $this->userResource->remove($args['id']);
        return $response->write($id);
    }

    public static function asJson(User $user) {
        $resourceMap = [];

        $resourceMap += [
            'id'                => function($user) { return $user->getId(); },
            'name'              => function($user) { return $user->getName(); },
            'email'             => function($user) { return $user->getEmail(); },
            'roles'             => function($user) { return $user->getRoles(); },
            'userGroupsIds'     => function($user) { return array_map(function($userGroup) { return $userGroup->getId(); }, $user->getUserGroups()->toArray()); },
            'creationTimestamp' => function($user) { return $user->getCreationTimestamp()->format('Y-m-d H:i:s'); },
        ];

        $ret = [];
        foreach($resourceMap as $key => $mapper) {
            $ret[$key] = $mapper($user);
        }
        return $ret;
    }
}