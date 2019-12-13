<?php

namespace App\Controllers;

use App\Repositories\AssetRepository;
use Slim\Http\Request;
use Slim\Http\Response;

class BlobController extends AbstractController {

    private $s3;

    public function __construct(ContainerInterface $container) {
        $s3Client = $container->get('s3');
        $bucket   = $container->get('config')->bucket;
        $this->s3 = new AssetRepository($s3Client, $bucket);
    }

    protected function get(Request $request, Response $response) {
        // $blobHash = $args['hash'];
        // $blob = $this->s3->retrieve($blobHash);
        // return $response->write($blob);
        return $response;
    }

    protected function post(Request $request, Response $response) {
        // $blob = $this->decodeRequestBody($request)->blob;
        // $blobHash = $this->s3->store($blob);
        // return $response->write(($blobHash));
        return $response;
    }

    protected function delete(Request $request, Response $response) {
        // $blobHash = $args['hash'];
        // $id = $this->s3->delete($blobHash);
        // write deleted id or sth
        return $response;
    }
}
