<?php

namespace App\Controllers;

use App\Entities\Folder;
use App\Resource\FolderResource;
use Slim\Http\Request;
use Slim\Http\Response;

class FolderController extends AbstractController {

    public static function asJson(Folder $folder) {
        $resourceMap = [];

        $resourceMap += [
            'id'                => function($folder) { return $folder->getId(); },
            'name'              => function($folder) { return $folder->getName(); },
            'creator'           => function($folder) { return UserController::asJson($folder->getCreator()); },
            'creationTimestamp' => function($folder) { return $folder->getCreationTimestamp()->format('Y-m-d H:i:s'); },
        ];

        $ret = [];
        foreach($resourceMap as $key => $mapper) {
            $ret[$key] = $mapper($folder);
        }
        return $ret;
    }

}
