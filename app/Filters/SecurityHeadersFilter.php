<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SecurityHeadersFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // No action before request
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Enterprise Security Headers
        $response->setHeader('X-Frame-Options', 'DENY');
        $response->setHeader('X-Content-Type-Options', 'nosniff');
        $response->setHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->setHeader('X-XSS-Protection', '1; mode=block');
        $response->setHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        return $response;
    }
}
