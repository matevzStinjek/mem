<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

final class PhotosController extends AbstractEntityController
{
    function handleGet(Request $request, Response $response) {
        $response->withJson();
        return $response;
    }
}
