<?php

namespace App\Controllers;

use App\Http\Request;
use App\Exceptions\InternalServerErrorException;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request as PsrRequest;
use Slim\Psr7\Response as PsrResponse;

abstract class AbstractController {

    protected $em;
    protected $user;

    public function __construct(ContainerInterface $container) {
        $this->em   = $container->get('em');
        $this->user = $container->get('user');
    }

    public function __invoke(PsrRequest $request, PsrResponse $response, array $args) {
        $handlerMethod = self::getHandlerMethod($request, $args);
        if (!method_exists($this, $handlerMethod)) {
            return $response->withStatus(405, 'Method does not exist on this endpoint.');
        }

        try {
            $request = new Request($request, $args);
            return $this->$handlerMethod($request, $response);
        } catch (InternalServerErrorException $e) {
            error_log($e->getMessage());
            return $response->withStatus(500, ':/ Sorry about that');
        }
    }

    private static function getHandlerMethod($request, $args) {
        $method = $request->getMethod();
        return $method === 'GET' && empty($args) ? 'getAll' : $method;
    }
}
