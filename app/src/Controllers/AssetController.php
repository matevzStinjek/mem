<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

final class AssetController extends AbstractController {

    protected function getAll(Request $request, Response $response, array $args) {
        error_log('test');
        return $response;
    }
}
