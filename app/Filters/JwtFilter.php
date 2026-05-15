<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class JwtFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (empty($authHeader) || !str_starts_with($authHeader, 'Bearer ')) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Token tidak ditemukan. Silakan login terlebih dahulu.',
                    'data'    => null
                ]);
        }

        $token = substr($authHeader, 7);

        try {
            $decoded = decodeJWT($token);
            $_SERVER['JWT_USER_ID'] = $decoded->userId;
            $_SERVER['JWT_EMAIL']   = $decoded->email;

        } catch (\Firebase\JWT\ExpiredException $e) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Token sudah kadaluarsa. Silakan login ulang.',
                    'data'    => null
                ]);

        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Token tidak valid.',
                    'data'    => null
                ]);

        } catch (\Exception $e) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Autentikasi gagal. Silakan login ulang.',
                    'data'    => null
                ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}