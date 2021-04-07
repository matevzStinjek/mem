<?php

namespace App\Controllers;

use App\Exceptions\UserException;
use App\Helpers\ResourceHelper;
use App\Http\Request;
use App\Model\Entities\Session;
use App\Resources\SessionResource;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Response;

class SessionController extends AbstractController {

    private $resource;

    public function __construct(ContainerInterface $container) {
        parent::__construct($container);
        $this->resource = new SessionResource($this->em);
    }

    public static function asJson(Session $session, $fields = null) {
        $resourceMap = [];

        $resourceMap += [
            'id' => function($session) { return $session->getId(); },
        ];

        return ResourceHelper::mapValues($session, $resourceMap, $fields);
    }
}
