<?php

use App\Controllers\AuthController;
use App\Middlewares\AuthMiddleware;

session_start();

spl_autoload_register(function ($class) {
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

$authMiddleware = new AuthMiddleware();
$authMiddleware->handle();

$authController = new AuthController();
$authController->route();
