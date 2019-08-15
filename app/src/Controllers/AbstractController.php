<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

abstract class AbstractController
{
    // protected $em = null;

    // public function __construct(PhotoResource $photoResource)
    // {
    //     $this->em = $entityManager;
    // }

    protected static function getDefaultFields()
    {
        return null;
    }

    public function handleRequest(Request $request, Response $response, array $args)
    {
        $handlerMethod = 'handle' . $request->getMethod();
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
}