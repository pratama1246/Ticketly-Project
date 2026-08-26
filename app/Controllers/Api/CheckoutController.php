<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\EventModel;
use App\Models\TicketTypeModel;
use App\Models\OrderModel;
use App\Models\OrderItemsModel;
use App\Models\PaymentMethodModel;
use Dompdf\Dompdf;
use SimpleSoftwareIO\QrCode\Generator as QrCodeGenerator;

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

        $db = \Config\Database::connect();

        // 0. Idempotency Key Check (Mencegah double order jika user double click / retry network)
        $idempotencyKey = trim((string) $this->request->getHeaderLine('X-Idempotency-Key'));
        $cacheKey       = null;
        if (!empty($idempotencyKey)) {
            $cacheKey   = 'idemp_' . md5($idempotencyKey . '_' . $userId);
            $cachedData = cache($cacheKey);
            if (!empty($cachedData) && is_array($cachedData)) {
                return $this->response->setStatusCode(200)->setJSON($cachedData);
            }
        }

        // Pemicu pembersihan order expired agar stok kembali sebelum pengecekan sisa stok tiket
        (new OrderModel())->autoExpireOrders();

        $rules = [
            'first_name'      => 'required|max_length[100]',
            'email'           => 'required|valid_email',
            'phone_number'    => 'required|max_length[20]|regex_match[/^[0-9+\-\s()]+$/]',
            'identity_number' => 'required|alpha_numeric|max_length[20]',
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

        $ticketIds = array_values(array_unique(array_filter(array_map(fn($t) => (int) ($t['ticket_type_id'] ?? 0), $ticketsRaw))));
        if (empty($ticketIds)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Format tiket tidak valid.',
                'data'    => null
            ]);
        }

        $ticketsList = $ticketModel->whereIn('id', $ticketIds)->findAll();
        $ticketsById = array_column($ticketsList, null, 'id');

        // 1. Validasi keberadaan tiket dan kecukupan stok
        foreach ($ticketsRaw as $t) {
            $tId = (int) $t['ticket_type_id'];
            if (!isset($ticketsById[$tId])) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status'  => 'error',
                    'message' => 'Tiket ID ' . $tId . ' tidak ditemukan.',
                    'data'    => null
                ]);
            }

            $ticketDb  = $ticketsById[$tId];
            $remaining = (int) $ticketDb['quantity_total'] - (int) $ticketDb['quantity_sold'];
            if ($remaining < (int) $t['quantity']) {
                return $this->response->setStatusCode(409)->setJSON([
                    'status'  => 'error',
                    'message' => 'Stok tiket "' . $ticketDb['name'] . '" tidak mencukupi.',
                    'data'    => null
                ]);
            }
        }

        // 2. Kalkulasi item dan total harga di memori
        $items    = [];
        $subTotal = 0;
        $totalQty = 0;

        foreach ($ticketsRaw as $t) {
            $ticket    = $ticketsById[(int) $t['ticket_type_id']];
            $qty       = (int) $t['quantity'];
            $lineTotal = (int) $ticket['price'] * $qty;

            $items[] = [
                'id'              => $ticket['id'],
                'event_id'        => $ticket['event_id'],
                'name'            => $ticket['name'],
                'ticket_category' => $ticket['ticket_category'],
                'price'           => (int) $ticket['price'],
                'quantity'        => $qty,
                'subtotal'        => $lineTotal,
            ];

            $subTotal += $lineTotal;
            $totalQty += $qty;
        }

        // 3. Kebijakan Anti-Calo / Anti-Scalper: Batas Maksimal 4 Tiket per NIK per Event
        $identityNumber = trim((string) $this->request->getVar('identity_number'));
        $eventId        = (int) $items[0]['event_id'];

        $alreadyBought = (int) ($db->table('order_items')
            ->join('orders', 'orders.id = order_items.order_id')
            ->join('ticket_types', 'ticket_types.id = order_items.ticket_type_id')
            ->where('ticket_types.event_id', $eventId)
            ->where('orders.identity_number', $identityNumber)
            ->whereIn('orders.status', ['pending', 'completed'])
            ->selectSum('order_items.quantity')
            ->get()
            ->getRow()
            ->quantity ?? 0);

        $maxLimitPerNik = 4;
        if (($alreadyBought + $totalQty) > $maxLimitPerNik) {
            $remainingQuota = max(0, $maxLimitPerNik - $alreadyBought);
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => "Batas maksimal pembelian untuk 1 NIK/Identitas pada event ini adalah {$maxLimitPerNik} tiket. Anda telah memesan {$alreadyBought} tiket (Sisa kuota: {$remainingQuota} tiket).",
                'data'    => null
            ]);
        }

        $taxAmount   = $subTotal * 0.11;
        $platformFee = 10000 * $totalQty;
        $adminFee    = 2500;
        $grandTotal  = $subTotal + $taxAmount + $platformFee + $adminFee;

        $db->transStart();

        $randomStr = strtoupper(bin2hex(random_bytes(4)));
        $trxId     = 'TRX-TKT-' . date('Ymd') . '-' . $randomStr;

        $paymentMethod = (string) $this->request->getVar('payment_method');
        $firstName     = (string) $this->request->getVar('first_name');
        $lastName      = (string) ($this->request->getVar('last_name') ?? '');
        $email         = (string) $this->request->getVar('email');
        $phoneNumber   = (string) $this->request->getVar('phone_number');

        $orderData = [
            'user_id'         => $userId,
            'trx_id'          => $trxId,
            'first_name'      => $firstName,
            'last_name'       => $lastName,
            'email'           => $email,
            'phone_number'    => $phoneNumber,
            'identity_number' => $identityNumber,
            'birth_date'      => $this->request->getVar('birth_date') ?? null,
            'payment_method'  => $paymentMethod,
            'order_total'     => $grandTotal,
            'status'          => 'pending',
        ];

        // Panggil Midtrans Core API (/v2/charge)
        $midtransService = new \App\Libraries\MidtransService();
        $chargeRes       = $midtransService->createCharge($orderData, $items);

        $orderData['payment_va_number']       = $chargeRes['payment_va_number'] ?? null;
        $orderData['payment_bill_key']        = $chargeRes['payment_bill_key'] ?? null;
        $orderData['payment_biller_code']     = $chargeRes['payment_biller_code'] ?? null;
        $orderData['payment_qr_url']          = $chargeRes['payment_qr_url'] ?? null;
        $orderData['payment_deeplink_url']    = $chargeRes['payment_deeplink_url'] ?? null;
        $orderData['payment_expired_at']      = $chargeRes['payment_expired_at'] ?? date('Y-m-d H:i:s', strtotime('+15 minutes'));
        $orderData['midtrans_transaction_id'] = $chargeRes['midtrans_transaction_id'] ?? null;
        $orderData['payment_status_raw']      = $chargeRes['payment_status_raw'] ?? 'pending';

        $orderModel->insert($orderData);
        $newOrderId = $orderModel->getInsertID();

        foreach ($items as $item) {
            $isSeating = strcasecmp($item['ticket_category'], 'Seating') === 0;

            // 4. Atomic Stock Check & Decrement (Mencegah Race Condition Over-selling)
            $db->query(
                'UPDATE ticket_types SET quantity_sold = quantity_sold + ? WHERE id = ? AND (quantity_sold + ?) <= quantity_total',
                [(int) $item['quantity'], (int) $item['id'], (int) $item['quantity']]
            );

            if ($db->affectedRows() === 0) {
                $db->transRollback();
                return $this->response->setStatusCode(409)->setJSON([
                    'status'  => 'error',
                    'message' => 'Stok untuk tiket "' . $item['name'] . '" baru saja habis atau tidak mencukupi.',
                    'data'    => null
                ]);
            }

            $assignedSeatIds = [];
            if ($isSeating) {
                $assignedSeatIds = $this->assignSeats(
                    $item['event_id'],
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
                    $item['event_id'],
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
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Gagal membuat order. Silakan coba lagi.',
                'data'    => null
            ]);
        }

        $responseData = [
            'status'  => 'success',
            'message' => 'Order berhasil dibuat.',
            'data'    => [
                'order_id'             => $newOrderId,
                'trx_id'               => $trxId,
                'grand_total'          => (int) $grandTotal,
                'status'               => 'pending',
                'payment_method'       => $paymentMethod,
                'payment_va_number'    => $orderData['payment_va_number'],
                'payment_bill_key'     => $orderData['payment_bill_key'],
                'payment_biller_code'  => $orderData['payment_biller_code'],
                'payment_qr_url'       => $orderData['payment_qr_url'],
                'payment_deeplink_url' => $orderData['payment_deeplink_url'],
                'expires_at'           => $orderData['payment_expired_at'],
            ]
        ];

        // Simpan hasil untuk Idempotency Key selama 60 detik
        if (!empty($idempotencyKey) && !empty($cacheKey)) {
            cache()->save($cacheKey, $responseData, 60);
        }

        return $this->response->setStatusCode(201)->setJSON($responseData);
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

        $orderModel      = new OrderModel();
        $orderItemsModel = new OrderItemsModel();
        $eventModel      = new EventModel();

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

        // fix: was 'Expired'
        if ($order['status'] === 'expired') {
            return $this->response->setStatusCode(410)->setJSON([
                'status'  => 'error',
                'message' => 'Order sudah kadaluarsa.',
                'data'    => null
            ]);
        }

        if ($order['status'] !== 'completed') {
            $midtransService = new \App\Libraries\MidtransService();
            $statusCheck     = $midtransService->checkTransactionStatus($order['trx_id']);

            if (!$statusCheck['is_paid']) {
                $trxStatus = $statusCheck['transaction_status'];
                if ($trxStatus === 'expire') {
                    $orderModel->update($orderId, ['status' => 'expired', 'payment_status_raw' => $trxStatus]);
                    return $this->response->setStatusCode(410)->setJSON([
                        'status'  => 'error',
                        'message' => 'Batas waktu pembayaran telah habis.',
                        'data'    => null,
                    ]);
                }
                if ($trxStatus === 'cancel' || $trxStatus === 'deny') {
                    $orderModel->update($orderId, ['status' => 'cancelled', 'payment_status_raw' => $trxStatus]);
                    return $this->response->setStatusCode(409)->setJSON([
                        'status'  => 'error',
                        'message' => 'Pembayaran dibatalkan atau ditolak oleh payment gateway.',
                        'data'    => null,
                    ]);
                }
                return $this->response->setStatusCode(400)->setJSON([
                    'status'  => 'error',
                    'message' => 'Pembayaran belum terdeteksi. Silakan selesaikan pembayaran terlebih dahulu.',
                    'data'    => null,
                ]);
            }

            $orderModel->update($orderId, [
                'status'             => 'completed',
                'payment_status_raw' => $statusCheck['transaction_status'],
            ]);
        }

        // --- Start of E-Ticket PDF and Email Dispatch ---
        $items = $orderItemsModel->select('order_items.*, seats.label, seats.seat_row, seats.seat_number, ticket_types.name as ticket_name, ticket_types.event_id') 
            ->join('seats', 'seats.id = order_items.seat_id', 'left')
            ->join('ticket_types', 'ticket_types.id = order_items.ticket_type_id', 'left')
            ->where('order_id', $orderId)
            ->findAll();

        if (!empty($items)) {
            $event = $eventModel->find($items[0]['event_id']);

            $ticketList = [];
            $qrCode = new QrCodeGenerator();

            foreach ($items as $item) {
                $seatLabel = 'Free Seating';
                if (!empty($item['label'])) {
                    $seatLabel = $item['label']; 
                } elseif (!empty($item['seat_row'])) {
                    $seatLabel = $item['seat_row'] . '-' . $item['seat_number'];
                }

                // Generate QR Unik per Tiket (SVG)
                $qrContent = !empty($item['ticket_code']) ? $item['ticket_code'] : 'ERR-' . $item['id'];
                $qrSvg = $qrCode->format('svg')->size(250)->generate($qrContent);
                $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

                $ticketList[] = [
                    'type' => $item['ticket_name'],
                    'seat' => $seatLabel,
                    'code' => $item['ticket_code'],
                    'qr'   => $qrBase64
                ];
            }

            $logoPath = FCPATH . 'assets/ticketly-logo.png';
            $logoBase64 = '';
            
            if (file_exists($logoPath)) {
                $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                $data = file_get_contents($logoPath);
                $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }

            try {
                $htmlContent = view('ticket_template', [
                    'event'     => $event,
                    'order'     => $order,
                    'tickets'   => $ticketList
                ]);

                $dompdf = new Dompdf();
                $dompdf->loadHtml($htmlContent);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $pdfOutput = $dompdf->output();

                $email = \Config\Services::email();
                $email->setFrom('noreply@ticketly.mytamakikii.web.id', 'Ticketly Admin'); 
                $email->setTo($order['email']);
                $email->setSubject('E-Tiket: ' . $event['name']);
                $email->setMessage("$htmlContent<br><br>Terima kasih...");
                $email->attach($pdfOutput, 'attachment', 'E-Ticket.pdf', 'application/pdf');

                $email->send();
            } catch (\Exception $e) {
                log_message('error', 'API Email Exception: ' . $e->getMessage());
            }
        }
        // --- End of E-Ticket PDF and Email Dispatch ---

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Pembayaran dikonfirmasi.',
            'data'    => [
                'order_id' => $orderId,
                'trx_id'   => $order['trx_id'],
                'status'   => 'completed',
                'email'    => $order['email'],
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

    // Private helper — seat assignment dengan Pessimistic Row Locking (FOR UPDATE)
    private function assignSeats($eventId, $ticketTypeId, $quantity)
    {
        $db = \Config\Database::connect();

        $takenSeatsSql = $db->table('order_items')
            ->select('seat_id')
            ->join('orders', 'orders.id = order_items.order_id')
            ->whereIn('orders.status', ['pending', 'completed'])
            ->where('seat_id IS NOT NULL')
            ->getCompiledSelect();

        $sql = "SELECT id FROM seats WHERE event_id = ? AND ticket_type_id = ?";
        if (!empty($takenSeatsSql)) {
            $sql .= " AND id NOT IN ({$takenSeatsSql})";
        }
        $sql .= " ORDER BY id ASC LIMIT ? FOR UPDATE";

        $query = $db->query($sql, [(int) $eventId, (int) $ticketTypeId, (int) $quantity]);
        $availableSeats = $query->getResultArray();

        if (count($availableSeats) < $quantity) {
            return false;
        }

        return array_column($availableSeats, 'id');
    }
}