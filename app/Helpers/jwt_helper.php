<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (!function_exists('createJWT')) {
    function createJWT($userId, $email) {
        $key = env('JWT_SECRET_KEY');
        $payload = [
            'iss'    => base_url(),
            'iat'    => time(),
            'exp'    => time() + (60 * 60 * 24 * 7), // Kadaluarsanya gitu...
            'userId' => $userId,
            'email'  => $email
        ];
        return JWT::encode($payload, $key, 'HS256');
    }
}

if (!function_exists('decodeJWT')) {
    function decodeJWT($token) {
        $key = env('JWT_SECRET_KEY');
        return JWT::decode($token, new Key($key, 'HS256'));
    }
}