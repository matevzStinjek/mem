<?php
namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

abstract class AbstractController {

    protected static function getDefaultFields() {
        return null;
    }

    public function handleRequest(Request $request, Response $response, array $args) {
        $handlerMethod = self::getHandlerMethod($request->getMethod(), count($args));
        if (method_exists($this, $handlerMethod)) {
            try {
                return $this->$handlerMethod($request, $response, $args);
            } catch (Exception $e) {
                return $response->withStatus(405, $e->getMessage());
            }
        } else {
            return $response->withStatus(405, 'Method not allowed. Allowed methods: ...');
        }
    }

    private function getHandlerMethod($method, $hasArgs) {
        return ($method === 'GET' && !$hasArgs) ? 'getAll' : $method;
    }

    // abstract asJson
}