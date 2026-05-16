<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\EventModel;
use App\Models\TicketTypeModel;
use App\Models\OrderModel;
use App\Models\OrderItemsModel;
use App\Models\PaymentMethodModel;

class CheckoutController extends BaseController
{
    // GET /api/checkout/payment-methods
    public function paymentMethods()
    {
        $paymentModel = new PaymentMethodModel();
        $methods      = $paymentModel->getActiveMethods();

        $grouped = [
            'ewallet'         => [],
            'virtual_account' => [],
            'other'           => [],
        ];

        foreach ($methods as $m) {
            $item = [
                'id'         => $m['id'],
                'name'       => $m['name'],
                'code'       => $m['code'],
                'type'       => $m['type'],
                'logo_image' => $m['logo_image']
                    ? base_url($m['logo_image'])
                    : null,
            ];

            if ($m['type'] === 'ewallet') {
                $grouped['ewallet'][] = $item;
            } elseif ($m['type'] === 'virtual_account') {
                $grouped['virtual_account'][] = $item;
            } else {
                $grouped['other'][] = $item;
            }
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Metode pembayaran berhasil diambil.',
            'data'    => $grouped
        ]);
    }

    // POST /api/checkout/calculate
    public function calculate()
    {
        $tickets = $this->request->getPost('tickets');

        if (empty($tickets) || !is_array($tickets)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Data tiket tidak valid.',
                'data'    => null
            ]);
        }

        $ticketModel = new TicketTypeModel();
        $items       = [];
        $subTotal    = 0;
        $totalQty    = 0;

        foreach ($tickets as $t) {
            $ticketId = (int) ($t['ticket_type_id'] ?? 0);
            $qty      = (int) ($t['quantity'] ?? 0);

            if ($ticketId <= 0 || $qty <= 0) continue;

            $ticket = $ticketModel->find($ticketId);
            if (!$ticket) continue;

            $lineTotal = (int) $ticket['price'] * $qty;
            $items[]   = [
                'ticket_type_id'  => $ticket['id'],
                'name'            => $ticket['name'],
                'ticket_category' => $ticket['ticket_category'],
                'price'           => (int) $ticket['price'],
                'quantity'        => $qty,
                'subtotal'        => $lineTotal,
            ];

            $subTotal += $lineTotal;
            $totalQty += $qty;
        }

        if (empty($items)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Tidak ada tiket valid yang dipilih.',
                'data'    => null
            ]);
        }

        $taxRate              = 0.11;
        $platformFeePerTicket = 10000;
        $adminFee             = 2500;
        $taxAmount            = $subTotal * $taxRate;
        $platformFee          = $platformFeePerTicket * $totalQty;
        $grandTotal           = $subTotal + $taxAmount + $platformFee + $adminFee;

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Kalkulasi berhasil.',
            'data'    => [
                'items'        => $items,
                'sub_total'    => (int) $subTotal,
                'tax_amount'   => (int) $taxAmount,
                'platform_fee' => (int) $platformFee,
                'admin_fee'    => (int) $adminFee,
                'grand_total'  => (int) $grandTotal,
            ]
        ]);
    }

    // POST /api/checkout/start (protected)
    public function start()
    {
        $userId = $_SERVER['JWT_USER_ID'] ?? null;

        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized. Silakan login terlebih dahulu.',
                'data'    => null
            ]);
        }

        $rules = [
            'first_name'      => 'required|string|max_length[100]',
            'email'           => 'required|valid_email',
            'phone_number'    => 'required|string|max_length[50]',
            'identity_number' => 'required|string|max_length[100]',
            'payment_method'  => 'required|string',
            'tickets'         => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Validasi gagal.',
                'data'    => $this->validator->getErrors()
            ]);
        }

        $body       = $this->request->getJSON(true);
        $ticketsRaw = $body['tickets'] ?? null;

        if (is_string($ticketsRaw)) {
            $ticketsRaw = json_decode($ticketsRaw, true);
        }

        if (empty($ticketsRaw) || !is_array($ticketsRaw)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Format tiket tidak valid.',
                'data'    => null
            ]);
        }

        $ticketModel     = new TicketTypeModel();
        $orderModel      = new OrderModel();
        $orderItemsModel = new OrderItemsModel();

        foreach ($ticketsRaw as $t) {
            
            $ticketDb = $ticketModel->find((int) $t['ticket_type_id']);
            if (!$ticketDb) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status'  => 'error',
                    'message' => 'Tiket ID ' . $t['ticket_type_id'] . ' tidak ditemukan.',
                    'data'    => null
                ]);
            }

            $remaining = $ticketDb['quantity_total'] - $ticketDb['quantity_sold'];
            if ($remaining < (int) $t['quantity']) {
                return $this->response->setStatusCode(409)->setJSON([
                    'status'  => 'error',
                    'message' => 'Stok tiket "' . $ticketDb['name'] . '" tidak mencukupi.',
                    'data'    => null
                ]);
            }
        }

        $items    = [];
        $subTotal = 0;
        $totalQty = 0;

        foreach ($ticketsRaw as $t) {
            $ticket    = $ticketModel->find((int) $t['ticket_type_id']);
            $qty       = (int) $t['quantity'];
            $lineTotal = (int) $ticket['price'] * $qty;

            $items[] = [
                'id'              => $ticket['id'],
                'name'            => $ticket['name'],
                'ticket_category' => $ticket['ticket_category'],
                'price'           => (int) $ticket['price'],
                'quantity'        => $qty,
                'subtotal'        => $lineTotal,
            ];

            $subTotal += $lineTotal;
            $totalQty += $qty;
        }

        $taxAmount   = $subTotal * 0.11;
        $platformFee = 10000 * $totalQty;
        $adminFee    = 2500;
        $grandTotal  = $subTotal + $taxAmount + $platformFee + $adminFee;

        $db = \Config\Database::connect();
        $db->transStart();

        $randomStr = strtoupper(bin2hex(random_bytes(4)));
        $trxId     = 'TRX-' . date('Ymd') . '-' . $randomStr;

        $orderModel->insert([
            'user_id'         => $userId,
            'trx_id'          => $trxId,
            'first_name'      => $this->request->getVar('first_name'),
            'last_name'       => $this->request->getVar('last_name') ?? '',
            'email'           => $this->request->getVar('email'),
            'phone_number'    => $this->request->getVar('phone_number'),
            'identity_number' => $this->request->getVar('identity_number'),
            'birth_date'      => $this->request->getVar('birth_date') ?? null,
            'payment_method'  => $this->request->getVar('payment_method'),
            'order_total'     => $grandTotal,
            'status'          => 'pending', // fix: was 'Pending'
        ]);

        $newOrderId = $orderModel->getInsertID();

        foreach ($items as $item) {
            $ticketDb  = $ticketModel->find($item['id']);
            $isSeating = strcasecmp($ticketDb['ticket_category'], 'Seating') === 0;

            $assignedSeatIds = [];
            if ($isSeating) {
                $assignedSeatIds = $this->assignSeats(
                    $ticketDb['event_id'],
                    $item['id'],
                    $item['quantity']
                );

                if ($assignedSeatIds === false) {
                    $db->transRollback();
                    return $this->response->setStatusCode(409)->setJSON([
                        'status'  => 'error',
                        'message' => 'Kursi untuk tiket "' . $item['name'] . '" baru saja habis.',
                        'data'    => null
                    ]);
                }
            }

            for ($i = 0; $i < $item['quantity']; $i++) {
                $randTicket = strtoupper(bin2hex(random_bytes(3)));
                $ticketCode = sprintf(
                    'TKT-E%02d-%d-%s',
                    $ticketDb['event_id'],
                    $newOrderId,
                    $randTicket
                );

                $orderItemsModel->insert([
                    'order_id'         => $newOrderId,
                    'ticket_type_id'   => $item['id'],
                    'quantity'         => 1,
                    'seat_id'          => $isSeating ? $assignedSeatIds[$i] : null,
                    'price_per_ticket' => $item['price'],
                    'ticket_code'      => $ticketCode,
                ]);
            }

            $ticketModel->where('id', $item['id'])
                ->set('quantity_sold', 'quantity_sold + ' . $item['quantity'], false)
                ->update();
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Gagal membuat order. Silakan coba lagi.',
                'data'    => null
            ]);
        }

        return $this->response->setStatusCode(201)->setJSON([
            'status'  => 'success',
            'message' => 'Order berhasil dibuat.',
            'data'    => [
                'order_id'    => $newOrderId,
                'trx_id'      => $trxId,
                'grand_total' => (int) $grandTotal,
                'status'      => 'pending', // fix: was 'Pending'
                'expires_at'  => date('Y-m-d H:i:s', strtotime('+15 minutes')),
            ]
        ]);
    }

    // POST /api/checkout/confirm (protected)
    public function confirm()
    {
        $userId = $_SERVER['JWT_USER_ID'] ?? null;

        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized. Silakan login terlebih dahulu.',
                'data'    => null
            ]);
        }

        $orderId = (int) $this->request->getVar('order_id');

        $orderModel = new OrderModel();
        $order      = $orderModel->where('user_id', $userId)
            ->where('id', $orderId)
            ->first();

        if (!$order) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Order tidak ditemukan.',
                'data'    => null
            ]);
        }

        // fix: was 'Expired'
        if ($order['status'] === 'expired') {
            return $this->response->setStatusCode(410)->setJSON([
                'status'  => 'error',
                'message' => 'Order sudah kadaluarsa.',
                'data'    => null
            ]);
        }

        if ($order['status'] !== 'completed') {
            $orderModel->update($orderId, ['status' => 'completed']);
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Pembayaran dikonfirmasi.',
            'data'    => [
                'order_id' => $orderId,
                'trx_id'   => $order['trx_id'],
                'status'   => 'completed',
            ]
        ]);
    }

    // POST /api/checkout/cancel (protected)
    public function cancel()
    {
        $userId = $_SERVER['JWT_USER_ID'] ?? null;

        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized. Silakan login terlebih dahulu.',
                'data'    => null
            ]);
        }

        $orderId = (int) $this->request->getVar('order_id');

        $orderModel      = new OrderModel();
        $orderItemsModel = new OrderItemsModel();
        $ticketModel     = new TicketTypeModel();

        $order = $orderModel->where('user_id', $userId)
            ->where('id', $orderId)
            ->first();

        if (!$order) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Order tidak ditemukan.',
                'data'    => null
            ]);
        }

        // fix: was 'Pending'
        if ($order['status'] !== 'pending') {
            return $this->response->setStatusCode(409)->setJSON([
                'status'  => 'error',
                'message' => 'Hanya order dengan status pending yang bisa dibatalkan.',
                'data'    => null
            ]);
        }

        $items = $orderItemsModel->where('order_id', $orderId)->findAll();
        foreach ($items as $item) {
            $ticketModel->where('id', $item['ticket_type_id'])
                ->set('quantity_sold', 'quantity_sold - ' . $item['quantity'], false)
                ->update();
        }

        // fix: was 'Cancelled'
        $orderModel->update($orderId, ['status' => 'cancelled']);

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Order berhasil dibatalkan.',
            'data'    => null
        ]);
    }

    // Private helper — seat assignment
    private function assignSeats($eventId, $ticketTypeId, $quantity)
    {
        $db = \Config\Database::connect();

        $takenSeatsSql = $db->table('order_items')
            ->select('seat_id')
            ->join('orders', 'orders.id = order_items.order_id')
            ->whereIn('orders.status', ['pending', 'completed']) // fix: was ['Pending', 'completed']
            ->where('seat_id IS NOT NULL')
            ->getCompiledSelect();

        $availableSeats = $db->table('seats')
            ->where('event_id', $eventId)
            ->where('ticket_type_id', $ticketTypeId)
            ->where("id NOT IN ($takenSeatsSql)", null, false)
            ->orderBy('id', 'ASC')
            ->limit($quantity)
            ->get()
            ->getResultArray();

        if (count($availableSeats) < $quantity) {
            return false;
        }

        return array_column($availableSeats, 'id');
    }
}