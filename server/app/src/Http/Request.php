<?php

namespace App\Http;

use Psr\Http\Message\ServerRequestInterface as PsrRequest;

class Request {

    private $user;
    private $body;
    private $args;
    private $params;
    private $headers;
    private $cookies;
    
    public function __construct(PsrRequest $request, $args, $user) {
        $this->user    = $user;
        $this->body    = (object)$request->getParsedBody();
        $this->args    = (object)$args;
        $this->params  = $request->getQueryParams();
        $this->headers = $request->getHeaders();
        $this->cookies = $request->getCookieParams();
        /** getServerParams, getUploadedFiles */
    }

    public function __get($name) {
        switch ($name) {
            case 'user':
                return $this->user;
            case 'body':
                return $this->body;
            case 'args':
                return $this->args;
            case 'params':
                return $this->params;
            case 'headers':
                return $this->headers;
            case 'cookies':
                return $this->cookies;
            default:
                throw new \Exception("Propery $name is not available.");
        }
    }
}
