<?php

namespace App;

class Config {

    private $config;

    const BASE_URL = 'http://localhost:8081';

    const COOKIE_NAME = 'mem-cooko';

    public function __construct($config) {
        $this->config = $config;
    }

    public function getSecret() {
        return $this->config['settings']['db']['secret'];
    }

    public function getBucketName() {
        return $this->config['settings']['aws']['s3']['meta']['bucket'];
    }

    public function getS3Config() {
        return $this->config['settings']['aws']['s3']['connection'];
    }

    public function getDoctrineConfig() {
        return $this->config['settings']['doctrine'];
    }

    public function getPublicDir() {
        return $this->config['publicDir'];
    }

    public function __get($name) {
        switch ($name) {
            case 'baseUrl':
                return self::BASE_URL;
            case 'cookieName':
                return self::COOKIE_NAME;
            case 'secret':
                return $this->getSecret();
            case 'bucket':
                return $this->getBucketName();
            case 's3':
                return $this->getS3Config();
            case 'doctrine':
                return $this->getDoctrineConfig();
            case 'publicDir':
                return $this->getPublicDir();
            default:
                throw new \Exception("Propery $name is not available.");
        }
    }
}