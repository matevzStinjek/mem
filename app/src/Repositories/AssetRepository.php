<?php

namespace App\Repositories;

use Aws\S3\S3Client;

class AssetRepository {

    private $bucket;
    private $s3;

    public function __construct(S3Client $s3, string $bucket) {
        $this->s3     = $s3;
        $this->bucket = $bucket;
    }

    public function store($blob) {
        $key = hash('sha256', $blob);

        return $this->s3->putObject([
            'Bucket' => $this->bucket,
            'Key'    => $key,
            'Body'   => $blob,
        ]);
    }

    public function retrieve($key) {
        return $this->s3->getObject([
            'Bucket' => $this->bucket,
            'Key'    => $key,
        ]);
    }

    public function delete($key) {
        return $this->s3->getObject([
            'Bucket' => $this->bucket,
            'Key'    => $key,
        ]);
    }
}