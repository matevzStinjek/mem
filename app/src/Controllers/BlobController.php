<?php

namespace App\Controllers;

use App\Repositories\AssetRepository;
use Slim\Http\Request;
use Slim\Http\Response;

final class BlobController extends AbstractController {

    private $s3;

    public function __construct(AssetRepository $s3) {
        $this->s3 = $s3;
    }

    protected function get(Request $request, Response $response, array $args) {
        $blobHash = $args['hash'];
        $blob = $this->s3->retrieve($blobHash);
        return $response->write($blob);
    }

    protected function post(Request $request, Response $response, array $args) {
        $blob = $this->decodeRequestBody($request)->blob;
        $blobHash = $this->s3->store($blob);
        return $response->write(($blobHash));
    }    

    protected function delete(Request $request, Response $response, array $args) {
        $blobHash = $args['hash'];
        $this->s3->delete($blobHash);
        return $response;
    }    
}
