<?php

namespace Api\Middlewares;

use Api\Helpers\JwtHelper;
use Exception;

class AuthMiddleware
{
    /**
     * @throws Exception
     */
    public static function authenticate(): void
    {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            http_response_code(401);

            die(json_encode(['error' => 'Unauthorized']));
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);

        try {
            JwtHelper::validateToken($token);
        } catch (Exception $e) {
            http_response_code(401);

            die(json_encode(['error' => 'Invalid token: ' . $e->getMessage()]));
        }
    }
}
