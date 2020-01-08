<?php

namespace App\Controllers;

use App\Http\Request;
use App\Exceptions\InternalServerErrorException;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request as PsrRequest;
use Slim\Psr7\Response;

abstract class AbstractController {

    protected $em;
    private $user;

    public function __construct(ContainerInterface $container) {
        $this->em      = $container->get('em');
        $this->user    = $container->get('user');
        $this->session = $container->get('session');
    }

    public function __invoke(PsrRequest $request, Response $response, array $args) {
        $handlerMethod = self::getHandlerMethod($request, $args);
        if (!method_exists($this, $handlerMethod)) {
            return $response->withStatus(405, 'Method does not exist on this endpoint.');
        }

        try {
            $request = new Request($request, $args, $this->user, $this->session);
            return $this->$handlerMethod($request, $response);
        } catch (InternalServerErrorException $e) {
            error_log($e->getMessage());
            return $response->withStatus(500, ':/ Sorry about that');
        }
    }

    private static function getHandlerMethod($request) {
        $method = $request->getMethod();
        return "handle$method";
    }

    protected function encodeResponseBody(Response $response, $body) {
        if (empty($body)) return;
        $response->getBody()->write(json_encode($body));
    }
}
