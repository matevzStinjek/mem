<?php

namespace App\Controllers;

use App\Repositories\AssetRepository;
use Slim\Http\Request;
use Slim\Http\Response;

final class AssetController extends AbstractController {

    private $assetResource;
    private $s3;

    public function __construct(AssetResource $assetResource, AssetRepository $s3) {
        $this->$assetResource = $assetResource;
        $this->s3 = $s3;
    }

    protected function getAll(Request $request, Response $response, array $args) {
        $blob = 'asfpiwejqiuasdasd'; // test
        $result = $this->s3->store($blob);
        return $response->withJson(var_dump($result));
    }
}
