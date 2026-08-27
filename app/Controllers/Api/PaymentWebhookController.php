<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemsModel;
use App\Models\TicketTypeModel;
use App\Models\EventModel;


class PaymentWebhookController extends BaseController
{
    /**
     * Webhook Handler Notifikasi Pembayaran Midtrans.
     * POST /api/payment/webhook
     */
    public function handle()
    {
        $payload = $this->request->getJSON(true);

        if (empty($payload)) {
            $rawInput = file_get_contents('php://input');
            $payload  = json_decode($rawInput, true) ?? [];
        }

        if (empty($payload)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Invalid JSON payload.',
                'data'    => null,
            ]);
        }

        $orderId           = $payload['order_id'] ?? null;
        $statusCode        = $payload['status_code'] ?? null;
        $grossAmount       = $payload['gross_amount'] ?? null;
        $signatureKey      = $payload['signature_key'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus       = $payload['fraud_status'] ?? 'accept';

        if (!$orderId || !$statusCode || !$grossAmount) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Parameter order_id, status_code, atau gross_amount tidak lengkap.',
                'data'    => null,
            ]);
        }

        $midtransConfig = config('Midtrans');
        $serverKey      = trim($midtransConfig->serverKey);

        // Verifikasi Signature jika Server Key terkonfigurasi di server
        if (!empty($serverKey) && !str_starts_with($serverKey, 'SB-Mid-server-YOUR_SERVER_KEY')) {
            $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

            if (!hash_equals($expectedSignature, (string) $signatureKey)) {
                log_message('error', "Midtrans Webhook: Invalid signature for order {$orderId}");
                return $this->response->setStatusCode(403)->setJSON([
                    'status'  => 'error',
                    'message' => 'Invalid signature key.',
                    'data'    => null,
                ]);
            }
        }

        $orderModel = new OrderModel();
        $order      = $orderModel->where('trx_id', $orderId)->first();

        if (!$order) {
            log_message('error', "Midtrans Webhook: Order {$orderId} not found in database.");
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Order tidak ditemukan.',
                'data'    => null,
            ]);
        }

        $currentStatus = strtolower($order['status']);

        // Idempotency: Jika order sudah selesai, tidak perlu diproses ulang
        if ($currentStatus === 'completed') {
            return $this->response->setStatusCode(200)->setJSON([
                'status'  => 'success',
                'message' => 'Order already completed.',
                'data'    => null,
            ]);
        }

        $orderItemsModel = new OrderItemsModel();
        $ticketModel     = new TicketTypeModel();

        // 1. Pembayaran Berhasil (settlement / capture)
        if ($transactionStatus === 'settlement' || ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
            $orderModel->update($order['id'], [
                'status'             => 'completed',
                'payment_status_raw' => $transactionStatus,
            ]);

            // Dispatch E-Tiket PDF dan Email Konfirmasi
            $this->dispatchTicketDelivery($order['id']);

            log_message('info', "Midtrans Webhook: Order {$orderId} successfully completed.");

        // 2. Pembayaran Kedaluwarsa (expire) -> Kembalikan stok tiket
        } elseif ($transactionStatus === 'expire') {
            $orderModel->update($order['id'], [
                'status'             => 'expired',
                'payment_status_raw' => $transactionStatus,
            ]);

            $this->restoreTicketInventory($order['id']);
            log_message('info', "Midtrans Webhook: Order {$orderId} expired, stock restored.");

        // 3. Pembayaran Dibatalkan / Ditolak (cancel / deny) -> Kembalikan stok tiket
        } elseif ($transactionStatus === 'cancel' || $transactionStatus === 'deny') {
            $orderModel->update($order['id'], [
                'status'             => 'cancelled',
                'payment_status_raw' => $transactionStatus,
            ]);

            $this->restoreTicketInventory($order['id']);
            log_message('info', "Midtrans Webhook: Order {$orderId} cancelled/denied, stock restored.");
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Webhook processed successfully.',
            'data'    => [
                'order_id'           => $orderId,
                'transaction_status' => $transactionStatus,
            ],
        ]);
    }

    /**
     * Mengembalikan kuota tiket saat pesanan kedaluwarsa atau dibatalkan.
     */
    protected function restoreTicketInventory(int $orderId): void
    {
        $orderItemsModel = new OrderItemsModel();
        $ticketModel     = new TicketTypeModel();

        $items = $orderItemsModel->where('order_id', $orderId)->findAll();
        foreach ($items as $item) {
            $ticketModel->where('id', $item['ticket_type_id'])
                ->set('quantity_sold', 'quantity_sold - ' . (int) $item['quantity'], false)
                ->update();
        }
    }

    /**
     * Menerbitkan E-Tiket PDF & Mengirimkan Email via TicketDeliveryService.
     */
    protected function dispatchTicketDelivery(int $orderId): void
    {
        \App\Libraries\TicketDeliveryService::process($orderId);
    }
}
