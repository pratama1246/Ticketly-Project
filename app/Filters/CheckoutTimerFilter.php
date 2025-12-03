<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CheckoutTimerFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Cek apakah proses checkout sedang berlangsung
        if (!$session->has('checkout_process')) {
            return redirect()->to('/');
        }

        // Cek Waktu
        $checkoutData = $session->get('checkout_process');
        $startTime = $checkoutData['start_time'];
        $maxTime = 300;

        if ((time() - $startTime) > $maxTime) {
            // WAKTU HABIS!
            $session->remove('checkout_process');
            return redirect()->to('/')->with('error', 'Waktu pemesanan telah habis.');
        }

        // Jika waktu masih ada, set sisa waktu untuk ditampilkan di view
        $timeLeft = $maxTime - (time() - $startTime);
        // Simpan sisa waktu ke session agar bisa diakses di view
        $session->set('checkout_time_left', $timeLeft);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}