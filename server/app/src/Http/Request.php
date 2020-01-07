<?php

namespace App\Http;

use Psr\Http\Message\ServerRequestInterface as PsrRequest;

class Request {

    /**
     * Request dispatcher
     * @var RegisteredUser
     */
    private $user;

    /**
     * Request body
     * @var object
     */
    private $body;

    /**
     * Request arguments
     * @var object
     */
    private $args;

    /**
     * Query parameters
     * @var array
     */
    private $params;

    /**
     * Requested fields parameter
     * @var array|null
     */
    private $fields;

    /**
     * Request headers
     * @var array
     */
    private $headers;

    /**
     * Request cookies
     * @var array
     */
    private $cookies;

    public function __construct(PsrRequest $request, $args, $user) {
        $this->user    = $user;
        $this->body    = (object)$request->getParsedBody();
        $this->args    = (object)$args;
        $this->params  = $request->getQueryParams();
        $this->fields  = isset($this->params['fields']) ? explode(',', $this->params['fields']) : null;
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
            case 'fields':
                return $this->fields;
            case 'headers':
                return $this->headers;
            case 'cookies':
                return $this->cookies;
            default:
                throw new \Exception("Propery $name is not available.");
        }
    }
}
