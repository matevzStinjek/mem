<?php

namespace App;

class Config {

    private $config;

    const BASE_URL = 'http://localhost:8081';

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

    public function __get($name) {
        switch ($name) {
            case 'baseUrl':
                return self::BASE_URL;
            case 'secret':
                return $this->getSecret();
            case 'bucket':
                return $this->getBucketName();
            case 's3':
                return $this->getS3Config();
            case 'doctrine':
                return $this->getDoctrineConfig();
            default:
                throw new \Exception("Propery $name is not available.");
        }
    }
}