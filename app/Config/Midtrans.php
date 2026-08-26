<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Midtrans extends BaseConfig
{
    /**
     * Server Key dari Dashboard Midtrans (Sandbox / Production).
     */
    public string $serverKey = '';

    /**
     * Client Key dari Dashboard Midtrans.
     */
    public string $clientKey = '';

    /**
     * Mode Lingkungan (false = Sandbox, true = Production).
     */
    public bool $isProduction = false;

    /**
     * Override URL Notifikasi Webhook (Opsional).
     * Jika diisi, header X-Override-Notification akan dikirim ke Midtrans.
     */
    public string $notificationUrl = '';

    public function __construct()
    {
        parent::__construct();

        $this->serverKey       = (string) env('MIDTRANS_SERVER_KEY', '');
        $this->clientKey       = (string) env('MIDTRANS_CLIENT_KEY', '');
        $this->isProduction    = filter_var(env('MIDTRANS_IS_PRODUCTION', false), FILTER_VALIDATE_BOOLEAN);
        $this->notificationUrl = (string) env('MIDTRANS_NOTIFICATION_URL', '');
    }

    /**
     * Mendapatkan URL API Charge sesuai mode (Sandbox / Production).
     */
    public function getChargeUrl(): string
    {
        return $this->isProduction
            ? 'https://api.midtrans.com/v2/charge'
            : 'https://api.sandbox.midtrans.com/v2/charge';
    }

    /**
     * Mendapatkan Header Authorization Basic Auth.
     */
    public function getAuthHeader(): string
    {
        return 'Basic ' . base64_encode($this->serverKey . ':');
    }
}
