<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (!function_exists('createJWT')) {
    function createJWT($userId, $email) {
        $key = env('JWT_SECRET_KEY');
        $payload = [
            'iss'    => base_url(),
            'iat'    => time(),
            'exp'    => time() + (60 * 60 * 24 * 7), // 7 hari
            'userId' => $userId,
            'email'  => $email,
            'jti'    => bin2hex(random_bytes(16)),   // Unique Token ID for revocation
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

if (!function_exists('blacklistJWT')) {
    /**
     * Masukkan token ke daftar blacklist cache saat user logout.
     */
    function blacklistJWT($token) {
        try {
            $decoded = decodeJWT($token);
            $remainingTtl = isset($decoded->exp) ? max(60, $decoded->exp - time()) : (60 * 60 * 24 * 7);
            $cacheKey = 'jwt_bl_' . md5($token);
            cache()->save($cacheKey, 1, $remainingTtl);
            return true;
        } catch (\Exception $e) {
            // Jika token invalid atau sudah expired, tetap blacklist hash-nya selama 1 jam
            cache()->save('jwt_bl_' . md5($token), 1, 3600);
            return false;
        }
    }
}

if (!function_exists('isJWTBlacklisted')) {
    /**
     * Periksa apakah token berada dalam daftar blacklist.
     */
    function isJWTBlacklisted($token): bool {
        $cacheKey = 'jwt_bl_' . md5($token);
        return (bool) cache($cacheKey);
    }
}