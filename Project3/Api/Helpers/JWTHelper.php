<?php

namespace Api\Helpers;

use Exception;

class JwtHelper
{

    /**
     * @var string
     */
    private static string $secret_key = 'secret_key';

    /**
     * @var string
     */
    private static string $algorithm = 'HS256';

    /**
     * @param string $data
     *
     * @return string
     */
    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * @param string $data
     *
     * @return string
     */
    private static function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    /**
     * @param array $payload
     *
     * @return string
     */
    public static function generateToken(array $payload): string
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => self::$algorithm]);
        $payload = json_encode($payload);

        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode($payload);
        $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", self::$secret_key, true);
        $base64UrlSignature = self::base64UrlEncode($signature);

        return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
    }

    /**
     * @param string $token
     *
     * @return array
     * @throws Exception
     */
    public static function validateToken(string $token): array
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            throw new Exception('Invalid token');
        }

        [$base64UrlHeader, $base64UrlPayload, $base64UrlSignature] = $parts;

        $header = json_decode(self::base64UrlDecode($base64UrlHeader), true);

        if ($header['alg'] !== self::$algorithm) {
            throw new Exception('Unsupported algorithm');
        }

        $payload = json_decode(self::base64UrlDecode($base64UrlPayload), true);

        $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", self::$secret_key, true);
        $expectedSignature = self::base64UrlEncode($signature);

        if (!hash_equals($expectedSignature, $base64UrlSignature)) {
            throw new Exception('Invalid signature');
        }

        return $payload;
    }
}
