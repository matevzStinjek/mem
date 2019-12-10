<?php

namespace App\Http;

use Psr\Http\Message\ServerRequestInterface as PsrRequest;

class Request {

    public $body;
    public $args;
    public $params;
    public $headers;
    public $cookies;
    
    public function __construct(PsrRequest $request, $args) {
        $this->body    = $request->getParsedBody();
        $this->args    = (object)$args;
        $this->params  = $request->getQueryParams();
        $this->headers = $request->getHeaders();
        $this->cookies = $request->getCookieParams();
        /** getServerParams, getUploadedFiles */
    }

    public function getBody() {
        return $this->body;
    }

    public function getArgs() {
        return $this->args;
    }

    public function getParams() {
        return $this->params;
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function getCookies() {
        return $this->cookies;
    }
}
