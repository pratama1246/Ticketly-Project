<?php

namespace App\Libraries;

use Config\Midtrans as MidtransConfig;

class MidtransService
{
    protected MidtransConfig $config;

    public function __construct()
    {
        $this->config = config('Midtrans');
    }

    /**
     * Membuat charge pembayaran langsung (Core API Direct Payment).
     *
     * @param array $orderData Data order (id, trx_id, first_name, last_name, email, phone_number, order_total, payment_method)
     * @param array $items     Data item tiket (opsional untuk item_details)
     * @return array [
     *     'success'                 => bool,
     *     'payment_va_number'       => ?string,
     *     'payment_bill_key'        => ?string,
     *     'payment_biller_code'     => ?string,
     *     'payment_qr_url'          => ?string,
     *     'payment_deeplink_url'    => ?string,
     *     'payment_expired_at'      => ?string,
     *     'midtrans_transaction_id' => ?string,
     *     'payment_status_raw'      => ?string,
     *     'message'                 => string,
     * ]
     */
    public function createCharge(array $orderData, array $items = []): array
    {
        $serverKey = trim($this->config->serverKey);

        // Jika Server Key belum disetting di .env, gunakan fallback dev mode
        if (empty($serverKey) || str_starts_with($serverKey, 'SB-Mid-server-YOUR_SERVER_KEY')) {
            log_message('warning', 'Midtrans: MIDTRANS_SERVER_KEY belum diisi di .env. Menggunakan sandbox dev fallback.');
            return $this->generateLocalDevFallback($orderData);
        }

        $paymentMethod = strtolower($orderData['payment_method'] ?? 'bca');
        $grossAmount   = (int) round((float) ($orderData['order_total'] ?? 0));
        $trxId         = $orderData['trx_id'];

        $payload = [
            'transaction_details' => [
                'order_id'     => $trxId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $orderData['first_name'],
                'last_name'  => $orderData['last_name'] ?? '',
                'email'      => $orderData['email'],
                'phone'      => $orderData['phone_number'],
            ],
            'custom_expiry' => [
                'expiry_duration' => 15,
                'unit'            => 'minute',
            ],
        ];

        // Format item details jika ada (Midtrans mewajibkan sum(item_details) === gross_amount)
        if (!empty($items)) {
            $itemDetails = [];
            $itemsSum    = 0;
            foreach ($items as $item) {
                $itemPrice = (int) round((float) ($item['price'] ?? 0));
                $itemQty   = (int) ($item['quantity'] ?? 1);
                $itemsSum += ($itemPrice * $itemQty);

                $itemDetails[] = [
                    'id'       => (string) ($item['id'] ?? 'item'),
                    'price'    => $itemPrice,
                    'quantity' => $itemQty,
                    'name'     => mb_substr((string) ($item['name'] ?? 'Tiket'), 0, 50),
                ];
            }

            $diff = $grossAmount - $itemsSum;
            if ($diff > 0) {
                $itemDetails[] = [
                    'id'       => 'TAX_AND_FEES',
                    'price'    => $diff,
                    'quantity' => 1,
                    'name'     => 'Pajak & Biaya Layanan',
                ];
            }

            $payload['item_details'] = $itemDetails;
        }

        // Petakan payment_method ke tipe Core API Midtrans
        switch ($paymentMethod) {
            case 'bca':
                $payload['payment_type']  = 'bank_transfer';
                $payload['bank_transfer'] = ['bank' => 'bca'];
                break;

            case 'bni':
                $payload['payment_type']  = 'bank_transfer';
                $payload['bank_transfer'] = ['bank' => 'bni'];
                break;

            case 'bri':
                $payload['payment_type']  = 'bank_transfer';
                $payload['bank_transfer'] = ['bank' => 'bri'];
                break;

            case 'allobank':
            case 'permata':
                $payload['payment_type']  = 'bank_transfer';
                $payload['bank_transfer'] = ['bank' => 'permata'];
                break;

            case 'mandiri_bill':
                $payload['payment_type'] = 'echannel';
                $payload['echannel']     = [
                    'bill_info1' => 'Ticketly:',
                    'bill_info2' => 'Order ' . $trxId,
                ];
                break;

            case 'gopay':
                $payload['payment_type'] = 'gopay';
                $payload['gopay']        = [
                    'enable_callback' => true,
                    'callback_url'    => base_url('profile/history'),
                ];
                break;

            case 'shopeepay':
                $payload['payment_type'] = 'shopeepay';
                $payload['shopeepay']    = [
                    'callback_url' => base_url('profile/history'),
                ];
                break;

            case 'ovo':
            case 'dana':
            case 'qris':
                $payload['payment_type'] = 'qris';
                $payload['qris']         = ['acquirer' => 'gopay'];
                break;

            case 'akulaku':
                $payload['payment_type'] = 'akulaku';
                break;

            default:
                // Default ke BCA VA jika tidak dikenali
                $payload['payment_type']  = 'bank_transfer';
                $payload['bank_transfer'] = ['bank' => 'bca'];
                break;
        }

        try {
            $client = \Config\Services::curlrequest([
                'timeout' => 15,
            ]);

            $headers = [
                'Authorization' => $this->config->getAuthHeader(),
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ];

            // Tambahkan header X-Override-Notification jika ada (menjamin multi-project tidak bentrok)
            if (!empty($this->config->notificationUrl)) {
                $headers['X-Override-Notification'] = $this->config->notificationUrl;
            }

            $response = $client->post($this->config->getChargeUrl(), [
                'headers'     => $headers,
                'json'        => $payload,
                'http_errors' => false,
            ]);

            $statusCode = $response->getStatusCode();
            $bodyRaw    = $response->getBody();
            $body       = json_decode($bodyRaw, true);

            if (($statusCode === 200 || $statusCode === 201) && !empty($body)) {
                return $this->parseMidtransResponse($body, $orderData);
            }

            log_message('error', 'Midtrans Charge Failed: HTTP ' . $statusCode . ' - ' . $bodyRaw);
            return $this->generateLocalDevFallback($orderData, $body['status_message'] ?? 'Midtrans charge failed.');

        } catch (\Exception $e) {
            log_message('error', 'Midtrans Charge Exception: ' . $e->getMessage());
            return $this->generateLocalDevFallback($orderData, $e->getMessage());
        }
    }

    /**
     * Mem-parsing response sukses dari Midtrans Core API.
     */
    protected function parseMidtransResponse(array $res, array $orderData): array
    {
        $vaNumber    = null;
        $billKey     = $res['bill_key'] ?? null;
        $billerCode  = $res['biller_code'] ?? null;
        $qrUrl       = null;
        $deeplinkUrl = null;

        // Ambil VA Number dari berbagai format response Midtrans
        if (!empty($res['va_numbers'][0]['va_number'])) {
            $vaNumber = $res['va_numbers'][0]['va_number'];
        } elseif (!empty($res['permata_va_number'])) {
            $vaNumber = $res['permata_va_number'];
        } elseif (!empty($res['bca_va_number'])) {
            $vaNumber = $res['bca_va_number'];
        }

        // Ambil QR Code & Deeplink URL dari array actions (GoPay / ShopeePay / QRIS)
        if (!empty($res['actions']) && is_array($res['actions'])) {
            foreach ($res['actions'] as $action) {
                if (($action['name'] ?? '') === 'generate-qr-code') {
                    $qrUrl = $action['url'] ?? null;
                } elseif (($action['name'] ?? '') === 'deeplink-redirect') {
                    $deeplinkUrl = $action['url'] ?? null;
                }
            }
        }

        // Ambil string QR jika tersedia langsung
        if (empty($qrUrl) && !empty($res['qr_string'])) {
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($res['qr_string']);
        }

        // Waktu expired
        $expiredAt = $res['expiry_time'] ?? date('Y-m-d H:i:s', strtotime('+15 minutes'));

        return [
            'success'                 => true,
            'payment_va_number'       => $vaNumber,
            'payment_bill_key'        => $billKey,
            'payment_biller_code'     => $billerCode,
            'payment_qr_url'          => $qrUrl,
            'payment_deeplink_url'    => $deeplinkUrl,
            'payment_expired_at'      => $expiredAt,
            'midtrans_transaction_id' => $res['transaction_id'] ?? null,
            'payment_status_raw'      => $res['transaction_status'] ?? 'pending',
            'message'                 => $res['status_message'] ?? 'Charge created successfully.',
        ];
    }

    /**
     * Fallback deterministik untuk lingkungan lokal / dev jika API key belum disetting.
     */
    protected function generateLocalDevFallback(array $orderData, string $msg = 'Dev sandbox mode'): array
    {
        $idDigits = preg_replace('/\D/', '', (string) ($orderData['trx_id'] ?? '1'));
        $suffix   = str_pad(substr($idDigits, -8), 8, '0', STR_PAD_LEFT);
        $method   = strtolower($orderData['payment_method'] ?? 'bca');

        $vaNumber   = null;
        $billKey    = null;
        $billerCode = null;
        $qrUrl      = null;

        if (in_array($method, ['bca', 'bni', 'bri', 'allobank', 'permata'], true)) {
            $prefix = match ($method) {
                'bca'      => '88000',
                'bni'      => '82770',
                'bri'      => '88004',
                default    => '89900',
            };
            $vaNumber = $prefix . $suffix;
        } elseif ($method === 'mandiri_bill') {
            $billerCode = '70012';
            $billKey    = '99' . $suffix;
        } elseif (in_array($method, ['gopay', 'shopeepay', 'ovo', 'dana', 'qris'], true)) {
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode('TICKETLY-SANDBOX-PAY-' . $orderData['trx_id']);
        }

        return [
            'success'                 => true,
            'payment_va_number'       => $vaNumber,
            'payment_bill_key'        => $billKey,
            'payment_biller_code'     => $billerCode,
            'payment_qr_url'          => $qrUrl,
            'payment_deeplink_url'    => null,
            'payment_expired_at'      => date('Y-m-d H:i:s', strtotime('+15 minutes')),
            'midtrans_transaction_id' => 'DEV-MID-' . strtoupper(bin2hex(random_bytes(6))),
            'payment_status_raw'      => 'pending',
            'message'                 => $msg,
        ];
    }

    /**
     * Mengecek status transaksi langsung ke Midtrans API (GET /v2/{order_id}/status).
     */
    public function checkTransactionStatus(string $trxId): array
    {
        $serverKey = trim($this->config->serverKey);
        if (empty($serverKey) || str_starts_with($serverKey, 'SB-Mid-server-YOUR_SERVER_KEY')) {
            return [
                'status_code'        => '200',
                'transaction_status' => 'pending',
                'fraud_status'       => 'accept',
                'is_paid'            => false,
                'message'            => 'Server Key belum disetting di .env (Dev Mode)',
            ];
        }

        $url = $this->config->isProduction
            ? "https://api.midtrans.com/v2/{$trxId}/status"
            : "https://api.sandbox.midtrans.com/v2/{$trxId}/status";

        try {
            $client = \Config\Services::curlrequest(['timeout' => 10]);
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => $this->config->getAuthHeader(),
                    'Accept'        => 'application/json',
                ],
                'http_errors' => false,
            ]);

            $statusCode = $response->getStatusCode();
            $bodyRaw    = $response->getBody();
            $body       = json_decode($bodyRaw, true) ?? [];

            $trxStatus   = $body['transaction_status'] ?? 'pending';
            $fraudStatus = $body['fraud_status'] ?? 'accept';
            $isPaid      = ($trxStatus === 'settlement' || ($trxStatus === 'capture' && $fraudStatus === 'accept'));

            return [
                'status_code'        => (string) $statusCode,
                'transaction_status' => $trxStatus,
                'fraud_status'       => $fraudStatus,
                'is_paid'            => $isPaid,
                'message'            => $body['status_message'] ?? 'Transaction status retrieved.',
            ];
        } catch (\Exception $e) {
            log_message('error', 'Midtrans checkTransactionStatus Exception: ' . $e->getMessage());
            return [
                'status_code'        => '500',
                'transaction_status' => 'pending',
                'fraud_status'       => 'accept',
                'is_paid'            => false,
                'message'            => $e->getMessage(),
            ];
        }
    }
}
