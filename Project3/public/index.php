<?php


use Api\Controllers\AuthController;
use Api\Controllers\ProductController;
use Api\Middlewares\AuthMiddleware;

spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/../';
    $path = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    if (file_exists($path)) {
        require_once $path;
    } else {
        http_response_code(500);
        die(json_encode(['error' => "Class file not found at path: $path"]));
    }
});

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

$routes = [
    ['POST', '/api/auth/login', [AuthController::class, 'login'], true],
    ['GET', '/api/products', [ProductController::class, 'index'], false],
    ['POST', '/api/products', [ProductController::class, 'store'], false],
    ['POST', '/api/products/{productId}/categories/{categoryId}', [ProductController::class, 'addCategory'], false],
    ['DELETE', '/api/products/{productId}/categories/{categoryId}', [ProductController::class, 'removeCategory'], false],
    ['POST', '/api/products/{productId}/tags/{tagId}', [ProductController::class, 'addTag'], false],
    ['DELETE', '/api/products/{productId}/tags/{tagId}', [ProductController::class, 'removeTag'], false],
];

foreach ($routes as $route) {
    [$routeMethod, $routeUri, $handler, $isPublic] = $route;

    if ($method === $routeMethod && $uri === $routeUri) {
        if (!$isPublic && in_array($method, ['POST', 'PUT', 'DELETE'])) {
            try {
                AuthMiddleware::authenticate();
            } catch (Exception $e) {
                http_response_code(401);

                die(json_encode(['error' => $e->getMessage()]));
            }
        }

        call_user_func($handler);

        exit;
    }
}

http_response_code(404);

echo json_encode(['error' => 'Route not found']);
