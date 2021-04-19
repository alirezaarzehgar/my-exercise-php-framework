<?php

use app\controllers\SiteController;
use app\core\Application;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USERNAME'],
        'password' => $_SERVER['DB_PASSWORD'],
    ],
];

$app = new Application(dirname(__DIR__), $config);

$app->router->get('/', [SiteController::class, 'home']);
$app->router->get('/login', [SiteController::class, 'login']);
$app->router->post('/login', [SiteController::class, 'login']);


$app->router->get('/register', [SiteController::class, 'register']);
$app->router->post('/register', [SiteController::class, 'register']);

$app->run();
