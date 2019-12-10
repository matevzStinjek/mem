<?php

namespace App\Controllers;

use App\Entity\User;
use App\Http\Request;
use App\Resource\UserResource;
use Slim\Http\Response;

final class UserController extends AbstractController {

    private $userResource;

    public function __construct(UserResource $userResource) {
        $this->userResource = $userResource;
    }

    protected function get(Request $request, Response $response) {
        // $user = $this->userResource->read($request->args);
        // $json = self::asJson($user);
        // return $response->withJson($json);
        return $response;
    }

    protected function getAll(Request $request, Response $response) {
        // $params = $request->getParams();
        // $users = $this->userResource->readAll($params);
        // $json = array_map(function($user) { return self::asJson($user); }, $users);
        // return $response->withJson($json);
        return $response;
    }

    protected function post(Request $request, Response $response) {
        // $entity = $this->decodeRequestBody($request);
        // $id = $this->userResource->create($entity);
        // return $response->write($id);
        return $response;
    }

    protected function put(Request $request, Response $response) {
        // $entity = $this->decodeRequestBody($request);
        // $id = $this->userResource->update($args['id'], $entity);
        // return $response->write($id);
        return $response;
    }

    protected function delete(Request $request, Response $response) {
        // $id = $this->userResource->remove($args['id']);
        // return $response->write($id);
        return $response;
    }

    public static function asJson(User $user) {
        $resourceMap = [];

        $resourceMap += [
            'id'                => function($user) { return $user->getId(); },
            'name'              => function($user) { return $user->getName(); },
            'email'             => function($user) { return $user->getEmail(); },
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