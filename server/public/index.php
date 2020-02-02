<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Application;
use Dotenv\Dotenv;

session_start();

$dotenv = Dotenv::create(__DIR__ . '/..');
$dotenv->load();

$config = require __DIR__ . '/../app/config/config.php';
$app = new Application($config);
$app->run();
