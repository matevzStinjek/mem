<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

final class PhotoController extends AbstractEntityController
{
    function handleGet(Request $request, Response $response, array $args) {
        return $response->withJson('tes');
    }
}
