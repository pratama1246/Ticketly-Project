<?php
if (!function_exists('apiResponse')) {
    function apiResponse($status, $data = null, $message = '', $code = 200) {
        return service('response')
            ->setStatusCode($code)
            ->setJSON([
                'status'  => $status,
                'message' => $message,
                'data'    => $data
            ]);
    }
}