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
            // Jika tidak ada session, paksa kembali ke halaman utama
            return redirect()->to('/');
        }

        // Cek Waktu
        $checkoutData = $session->get('checkout_process');
        $startTime = $checkoutData['start_time'];
        $maxTime = 900; // 15 menit x 60 detik

        if ((time() - $startTime) > $maxTime) {
            // WAKTU HABIS!
            // Hancurkan session checkout dan kirim ke halaman "waktu habis"
            $session->remove('checkout_process');

            // Buat view 'checkout_timeout' untuk pesan error
            return redirect()->to('/checkout/timeout'); 
        }

        // Jika waktu masih ada, set sisa waktu untuk ditampilkan di view
        $timeLeft = $maxTime - (time() - $startTime);
        // Simpan sisa waktu ke session agar bisa diakses di view
        $session->set('checkout_time_left', $timeLeft);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu melakukan apa-apa setelahnya
    }
}