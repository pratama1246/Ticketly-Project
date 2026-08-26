<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ApiRateLimitFilter implements FilterInterface
{
    /**
     * Rate limiter untuk API endpoints (Auth, Checkout, Sensitive Actions).
     *
     * @param array|null $arguments [0 => maxRequests, 1 => perSeconds]
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! $request instanceof IncomingRequest) {
            return;
        }

        $throttler = service('throttler');

        $maxRequests = ! empty($arguments[0]) ? (int) $arguments[0] : 10;
        $costSeconds = ! empty($arguments[1]) ? (int) $arguments[1] : 60;

        $key = 'api_rl_' . md5($request->getIPAddress() . '_' . $request->getPath());

        if ($throttler->check($key, $maxRequests, $costSeconds, 1) === false) {
            $retryAfter = $throttler->getTokenTime();

            return service('response')
                ->setStatusCode(429)
                ->setHeader('Retry-After', (string) $retryAfter)
                ->setJSON([
                    'status'  => 'error',
                    'message' => "Terlalu banyak permintaan. Silakan coba lagi dalam {$retryAfter} detik.",
                    'data'    => null,
                ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
