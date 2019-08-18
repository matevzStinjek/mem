<?php

require __DIR__ . '/../vendor/autoload.php';

session_start();

$dotenv = Dotenv\Dotenv::create(__DIR__ . '/..');
$dotenv->load();

// Instantiate the app
$settings = require __DIR__ . '/../app/settings.php';
$app = new \Slim\App($settings);

// dependencies
require __DIR__ . '/../app/dependencies.php';

// middleware
require __DIR__ . '/../app/middleware.php';

// routes
require __DIR__ . '/../app/routes.php';

$app->run();
