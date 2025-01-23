<?php

namespace App\Middlewares;

use App\Controllers\AuthController;

class AuthMiddleware
{
    public function handle(): void
    {
        $action = $_GET['action'] ?? AuthController::ACTION_LOGIN;

        if (!isset($_SESSION['username']) && $action === AuthController::ACTION_PROFILE) {
            AuthController::redirect(AuthController::ACTION_LOGIN);
        }

        if (isset($_SESSION['username']) && !in_array($action, AuthController::ALLOWED_ACTIONS)) {
            AuthController::redirect(AuthController::ACTION_PROFILE);
        }
    }
}
