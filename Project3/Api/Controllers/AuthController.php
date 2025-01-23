<?php

namespace Api\Controllers;

use Api\Helpers\JwtHelper;

class AuthController
{
    public static function login(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        error_log("Input data: " . json_encode($data));

        if ($data['username'] === 'root' && $data['password'] === '') {
            $payload = ['user' => 'root', 'exp' => time() + 3600];
            $token = JwtHelper::generateToken($payload);

            echo json_encode(['token' => $token]);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
        }
    }
}
