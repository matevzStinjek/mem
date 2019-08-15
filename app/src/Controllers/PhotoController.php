<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

final class PhotoController extends AbstractController
{
    function handleGet(Request $request, Response $response, array $args) {
        $response->withJson('test');
        return $response;
    }
}
