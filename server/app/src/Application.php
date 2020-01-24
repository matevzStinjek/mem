<?php

namespace App;

use App\Config;
use App\Filters\FilterChain;
use App\Http\Router;
use App\Model\ORM\EntityManagerFactory;
use Aws\S3\S3Client;
use DI\Container;
use Slim\App;
use Slim\Psr7\Factory\ResponseFactory;

class Application extends App {

    private $config;

    public function __construct($config) {
        $this->config = new Config($config);
        $container = $this->createContainer();
        parent::__construct(new ResponseFactory, $container);
        $this->registerMiddleware();
        $this->registerRoutes();
    }

    private function createContainer() {
        $container = new Container;
        $container->set('em', $this->createEntityManager());
        $container->set('s3', $this->createS3Client());
        $container->set('config', $this->config);
        return $container;
    }

    private function registerMiddleware() {
        FilterChain::registerMiddleware($this);
    }

    private function registerRoutes() {
        Router::registerRouteCallbacks($this);
    }

    public function createEntityManager() {
        return EntityManagerFactory::create($this->config->doctrine);
    }

    public function createS3Client() {
        return new S3Client($this->config->s3);
    }
}
