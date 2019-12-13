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
        $blobHash = hash('sha256', $blob);

        $this->s3->putObject([
            'Bucket' => $this->bucket,
            'Key'    => $blobHash,
            'Body'   => $blob,
        ]);

        return $blobHash;
    }

    public function retrieve($key) {
        $result = $this->s3->getObject([
            'Bucket' => $this->bucket,
            'Key'    => $key,
        ]);

        return $result['Body'];
    }

    public function delete($key) {
        $this->s3->deleteObject([
            'Bucket' => $this->bucket,
            'Key'    => $key,
        ]);
    }
}