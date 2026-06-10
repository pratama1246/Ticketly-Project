<?php

/**
 * router.php — Custom PHP built-in server router untuk development
 *
 * Menambahkan CORS header ke SEMUA response termasuk file statis.
 * Dipakai otomatis via: php spark serve (override di app/Commands/Serve.php)
 *
 * Fix: gunakan MIME type map berbasis ekstensi karena mime_content_type()
 * sering return 'text/plain' untuk .css dan .js sehingga browser reject.
 */

// CORS header untuk semua response
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Handle preflight OPTIONS langsung
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Resolve path — pakai parse_url agar aman dari query string & fragments
$uri      = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$filePath = __DIR__ . $uri;

if (is_file($filePath)) {
    // Gunakan MIME type map berbasis ekstensi (lebih reliable dari mime_content_type)
    $ext       = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $mimeTypes = [
        'css'   => 'text/css',
        'js'    => 'application/javascript',
        'mjs'   => 'application/javascript',
        'html'  => 'text/html',
        'json'  => 'application/json',
        'png'   => 'image/png',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'gif'   => 'image/gif',
        'svg'   => 'image/svg+xml',
        'webp'  => 'image/webp',
        'ico'   => 'image/x-icon',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf'   => 'font/ttf',
        'otf'   => 'font/otf',
        'pdf'   => 'application/pdf',
        'txt'   => 'text/plain',
        'xml'   => 'application/xml',
        'map'   => 'application/json',
    ];
    $mimeType = $mimeTypes[$ext] ?? 'application/octet-stream';

    header('Content-Type: ' . $mimeType);
    header('Content-Length: ' . filesize($filePath));
    header('Cache-Control: public, max-age=86400');
    readfile($filePath);
    exit;
}

// Semua non-file request → CI4 framework
require_once __DIR__ . '/index.php';

