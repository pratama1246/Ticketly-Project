<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\TicketTypeModel;
use App\Models\OrderModel;
use App\Models\OrderItemsModel;
use App\Models\PaymentMethodModel;

use Dompdf\Dompdf;
use SimpleSoftwareIO\QrCode\Generator as QrCodeGenerator;

class CheckoutController extends BaseController
{

    // LANGKAH 1: MEMULAI PROSES CHECKOUT

    public function start()
    {
        if (session()->has('checkout_process')) {
            if (session()->has('checkout_expire') && session()->get('checkout_expire') > time()) {
                return redirect()->to('/checkout/review_order')
                    ->with('warning', 'Harap selesaikan atau batalkan pesanan Anda sebelumnya.');
            } else {
                session()->remove('checkout_process');
                session()->remove('checkout_expire');
            }
        }

        $quantities = $this->request->getPost('quantity');
        $eventId = $this->request->getPost('eventId');

        if (empty($quantities) || empty($eventId)) {
            return redirect()->back()->with('error', 'Silakan pilih tiket terlebih dahulu.');
        }

        $selectedTickets = array_filter($quantities, function ($qty) {
            return $qty > 0;
        });

        if (empty($selectedTickets)) {
            return redirect()->back()->with('error', 'Silakan pilih minimal 1 tiket.');
        }

        if (session()->has('checkout_process')) {
            $currentData = session('checkout_process');
            if ($currentData['event_id'] != $eventId) {
                session()->setFlashdata('warning', 'Sesi checkout sebelumnya telah diganti dengan event baru.');
            }
        }

        $checkoutData = [
            'event_id'       => $eventId,
            'selected_tickets' => $selectedTickets,
            'start_time'       => time(),
            'personal_data'    => null,
            'payment_method'   => null
        ];
        
        session()->set('checkout_process', $checkoutData);
        session()->set('checkout_expire', time() + 300); // 5 Menit

        return redirect()->to('/checkout/personal_info');
    }


    // LANGKAH 2: FORM DATA DIRI (GET)

    public function personalInfo()
    {   
        if (session()->has('pending_order_id')) {
            return redirect()->to('/checkout/pay/' . session()->get('pending_order_id'));
        }

        $timeLeft = $this->checkSessionTime();
        if ($timeLeft === false) {
            return redirect()->to('/')->with('error', 'Waktu pemesanan habis.');
        }

        $eventModel = new EventModel();
        $checkoutData = session('checkout_process');
        
        if (empty($checkoutData)) {
            return redirect()->to('/');
        }
        
        $data['event'] = $eventModel->find($checkoutData['event_id']);
        $data['step'] = 1;
        $data['time_left'] = $timeLeft;

        echo view('layout/header_checkout', $data);
        echo view('checkout_personal_info', $data); 
        echo view('layout/footer');
    }


    // LANGKAH 3: PROSES DATA DIRI (POST)

    public function processPersonalInfo()
    {
        $rules = [
            'first_name'      => 'required|string|max_length[100]',
            'email'           => 'required|valid_email|max_length[255]',
            'phone_number'    => 'required|string|max_length[50]',
            'identity_number' => 'required|string|max_length[100]',
            'last_name'       => 'permit_empty|string|max_length[100]',
            'birth_date'      => 'required|string',
        ];

        $rawDate = $this->request->getPost('birth_date');
        $fixedDate = null;

        if (!empty($rawDate)) {
            $dateObject = \DateTime::createFromFormat('d/m/Y', $rawDate);
            if ($dateObject) {
                $fixedDate = $dateObject->format('Y-m-d');
            } else {
                $fixedDate = $rawDate; 
            }
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $sessionData = session('checkout_process');
        $sessionData['personal_data'] = [
            'first_name'      => $this->request->getPost('first_name'),
            'last_name'       => $this->request->getPost('last_name'),
            'email'           => $this->request->getPost('email'),
            'phone_number'    => $this->request->getPost('phone_number'),
            'identity_number' => $this->request->getPost('identity_number'),
            'birth_date'      => $fixedDate
        ];
        
        session()->set('checkout_process', $sessionData);
        return redirect()->to('/checkout/payment_method');
    }


    // LANGKAH 4: PILIH METODE PEMBAYARAN (GET)

    public function paymentMethod()
    {
        if (session()->has('pending_order_id')) {
            return redirect()->to('/checkout/pay/' . session()->get('pending_order_id'));
        }

        $timeLeft = $this->checkSessionTime();
        if ($timeLeft === false) { return redirect()->to('/'); }

        $paymentModel = new PaymentMethodModel();
        $methods = $paymentModel->getActiveMethods();

        $data = [
            'step' => 2,
            'time_left' => $timeLeft,
            'ewallets' => array_filter($methods, fn($m) => $m['type'] == 'ewallet'),
            'vas'      => array_filter($methods, fn($m) => $m['type'] == 'virtual_account'),
            'others'   => array_filter($methods, fn($m) => $m['type'] == 'other')
        ];

        echo view('layout/header_checkout', $data);
        echo view('checkout_payment_method', $data);
        echo view('layout/footer');
    }


    // LANGKAH 5: PROSES METODE PEMBAYARAN (POST)

    public function processPayment()
    {
        $rules = [
            'payment_method' => 'required|string'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Silakan pilih satu metode pembayaran.');
        }

        $sessionData = session('checkout_process');
        $sessionData['payment_method'] = $this->request->getPost('payment_method');
        session()->set('checkout_process', $sessionData);

        return redirect()->to('/checkout/review_order');
    }


    // LANGKAH 6: REVIEW PESANAN (GET)

    public function reviewOrder()
    {
        $timeLeft = $this->checkSessionTime();
        if ($timeLeft === false) { return redirect()->to('/'); }

        $session = session();
        $checkoutData = $session->get('checkout_process');

        if (empty($checkoutData['personal_data']) || empty($checkoutData['payment_method'])) {
            return redirect()->to('/checkout/personal_info');
        }

        $eventModel = new EventModel();
        $data['event'] = $eventModel->find($checkoutData['event_id']);
        $data['personal'] = $checkoutData['personal_data'];
        $data['payment_method'] = $checkoutData['payment_method'];
        
        $paymentModel = new PaymentMethodModel();
        $method = $paymentModel->where('code', $checkoutData['payment_method'])->first();
        $data['payment_method_name'] = $method ? $method['name'] : $checkoutData['payment_method'];
        
        // GUNAKAN HELPER CALCULATE
        $calculation = $this->calculateTransaction($checkoutData['selected_tickets']);

        $data['selected_tickets_details'] = $calculation['items'];
        $data['sub_total']      = $calculation['sub_total'];
        $data['tax_amount']     = $calculation['tax_amount'];
        $data['platform_fee']   = $calculation['platform_fee'];
        $data['admin_fee']      = $calculation['admin_fee'];
        $data['grand_total']    = $calculation['grand_total'];

        $data['step'] = 3;
        $data['time_left'] = $timeLeft;

        echo view('layout/header_checkout', $data);
        echo view('checkout_review_order', $data);
        echo view('layout/footer');
    }


    // LANGKAH 7: BUAT ORDER & SIMPAN KE DB (POST)

    public function createOrder()
    {
        $session = session();
        $checkoutData = $session->get('checkout_process');
        if (empty($checkoutData)) { return redirect()->to('/'); }

        $orderModel = new OrderModel();
        $orderItemsModel = new OrderItemsModel();
        $ticketTypeModel = new TicketTypeModel();
        
        // 1. Hitung Ulang Total
        $calculation = $this->calculateTransaction($checkoutData['selected_tickets']);

        // 2. Cek Stok Lagi
        foreach ($calculation['items'] as $item) {
            $ticketDb = $ticketTypeModel->find($item['id']);
            if ($ticketDb['quantity_sold'] + $item['quantity'] > $ticketDb['quantity_total']) {
                return redirect()->back()->with('error', 'Stok tiket ' . $ticketDb['name'] . ' tidak mencukupi.');
            }
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $randomStr = strtoupper(bin2hex(random_bytes(4))); 
        $trxId = 'TRX-' . date('Ymd') . '-' . $randomStr;
        $personalData = $checkoutData['personal_data'];
        
        $orderData = [
            'user_id'         => auth()->loggedIn() ? auth()->id() : null,
            'trx_id'          => $trxId,
            'first_name'      => $personalData['first_name'],
            'last_name'       => $personalData['last_name'],
            'email'           => $personalData['email'],
            'phone_number'    => $personalData['phone_number'],
            'identity_number' => $personalData['identity_number'],
            'birth_date'      => $personalData['birth_date'],
            'payment_method'  => $checkoutData['payment_method'],
            'order_total'     => $calculation['grand_total'],
            'status'          => 'Pending'
        ];
        
        $orderModel->insert($orderData);
        $newOrderId = $orderModel->getInsertID();

        foreach ($calculation['items'] as $ticket) {
            $ticketInfo = $ticketTypeModel->find($ticket['id']);
            
            // FIX: Case-insensitive check untuk 'Seating'
            $isSeating = (strcasecmp($ticketInfo['ticket_category'], 'Seating') === 0);

            $assignedSeatId = [];

            if ($isSeating) {
                $assignedSeatId = $this->assignSeats($checkoutData['event_id'], $ticket['id'], $ticket['quantity']);

                if ($assignedSeatId === false) {
                    $db->transRollback();
                    return redirect()->to('/checkout/review_order')
                     ->with('error', 'Mohon maaf, kursi untuk tiket ' . $ticketInfo['name'] . ' baru saja habis. Silakan coba lagi.');
                }        
            }

            for ($i = 0; $i < $ticket['quantity']; $i++) {
                $randTicket = strtoupper(bin2hex(random_bytes(3))); 
                $ticketUniqueCode = sprintf('TKT-E%02d-%d-%s', $checkoutData['event_id'], $newOrderId, $randTicket);

                $orderItemsModel->insert([
                    'order_id'       => $newOrderId,
                    'ticket_type_id' => $ticket['id'],
                    'quantity'       => 1,
                    'seat_id'        => $isSeating ? $assignedSeatId[$i] : null,
                    'price_per_ticket' => $ticket['price'],
                    'ticket_code'    => $ticketUniqueCode
                ]);
            }

            $ticketTypeModel->where('id', $ticket['id'])
                            ->set('quantity_sold', 'quantity_sold + ' . $ticket['quantity'], false)
                            ->update();
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/checkout/review_order')->with('error', 'Gagal membuat pesanan. Silakan coba lagi.');
        }

        $session->remove('checkout_process');
        $session->remove('checkout_expire');
        $session->set('pending_order_id', $newOrderId);

        return redirect()->to('/checkout/pay/' . $newOrderId);
    }


    // LANGKAH 8: HALAMAN PEMBAYARAN (GET)

    public function pay($orderId)
    {
        $orderModel = new OrderModel();
        $eventModel = new EventModel();
        $orderItemsModel = new OrderItemsModel();
        $ticketTypeModel = new TicketTypeModel(); 

        $order = $orderModel->find($orderId);

        if (!$order) {
            return redirect()->to('/');
        }

        $createdAt = strtotime($order['created_at']);
        $deadline  = $createdAt + (15 * 60);
        $now       = time();
        $timeLeft  = $deadline - $now;

        if ($timeLeft < 0) { $timeLeft = 0; }

        $item = $orderItemsModel->where('order_id', $orderId)->first();
        $ticket = $ticketTypeModel->find($item['ticket_type_id']);
        $event = $eventModel->find($ticket['event_id']);

        $data = [
            'title'     => 'Menunggu Pembayaran',
            'order'     => $order,
            'event'     => $event,
            'step'      => 4,
            'time_left' => $timeLeft,
            'enable_floating_timer' => false
        ];

        echo view('layout/header_checkout', $data);
        echo view('checkout_pay', $data);
        echo view('layout/footer');
    }

    // LANGKAH 9: KONFIRMASI PEMBAYARAN & KIRIM E-TICKET (AJAX POST)

    public function confirmPayment($orderId)
    {   
        error_reporting(0);

        if (!$this->request->isAJAX()) { return redirect()->to('/'); }

        $orderModel = new OrderModel();
        $orderItemsModel = new OrderItemsModel();
        $eventModel = new EventModel();
        
        $order = $orderModel->find($orderId);
        if (!$order) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Order not found']);
        }

        if ($order['status'] != 'completed') {
            $orderModel->update($orderId, ['status' => 'completed']);
        }

        // 1. QUERY SAKTI: Ambil semua data Item + Kursi + Nama Tiket sekaligus
        $items = $orderItemsModel->select('order_items.*, seats.label, seats.seat_row, seats.seat_number, ticket_types.name as ticket_name, ticket_types.event_id') 
            ->join('seats', 'seats.id = order_items.seat_id', 'left')
            ->join('ticket_types', 'ticket_types.id = order_items.ticket_type_id', 'left') // Join biar dapet nama tiket per item
            ->where('order_id', $orderId)
            ->findAll();

        if (empty($items)) {
             return $this->response->setJSON(['status' => 'error', 'message' => 'Tiket tidak ditemukan']);
        }

        // Ambil Data Event dari item pertama (karena 1 order pasti 1 event)
        $event = $eventModel->find($items[0]['event_id']);

        // 2. LOOPING DATA TIKET: Siapkan data unik untuk SETIAP tiket
        $ticketList = [];
        $qrCode = new QrCodeGenerator();

        foreach ($items as $item) {
            // Logic Nama Kursi per Tiket
            $seatLabel = 'Free Seating'; // Default kalau standing
            if (!empty($item['label'])) {
                $seatLabel = $item['label']; 
            } elseif (!empty($item['seat_row'])) {
                $seatLabel = $item['seat_row'] . '-' . $item['seat_number'];
            }

            // Generate QR Unik per Tiket
            $qrContent = !empty($item['ticket_code']) ? $item['ticket_code'] : 'ERR-' . $item['id'];
            $qrBase64 = base64_encode($qrCode->format('png')->size(150)->generate($qrContent));

            $ticketList[] = [
                'type' => $item['ticket_name'], // Nama Tiket (misal: CAT 1 / Festival)
                'seat' => $seatLabel,           // Kursi (misal: A-1 / Free Seating)
                'code' => $item['ticket_code'], // Kode Tiket Unik
                'qr'   => $qrBase64             // QR Code Unik
            ];
        }

        // 3. Kirim Array $tickets ke View
        $pdfData = [
            'event'     => $event,
            'order'     => $order,
            'tickets'   => $ticketList // <--- Ini array isinya BANYAK tiket
        ];

        try {
            $dompdf = new Dompdf();
            $dompdf->loadHtml(view('ticket_template', $pdfData)); // Load view baru
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $pdfOutput = $dompdf->output();

            $email = \Config\Services::email();
            $email->setTo($order['email']);
            $email->setSubject('E-Tiket: ' . $event['name']);
            $email->setMessage("Halo {$order['first_name']}, ini tiket kamu. Total ada " . count($items) . " tiket ya!");
            $email->attach($pdfOutput, 'attachment', 'E-Tickets-' . $order['trx_id'] . '.pdf', 'application/pdf');
            $email->send();
        } catch (\Exception $e) {
            // Ignore
        }

        session()->remove('checkout_process');
        session()->remove('checkout_expire');
        session()->remove('pending_order_id');

        return $this->response->setJSON([
            'status'     => 'success', 
            'email'      => $order['email'], 
            'trx_id'     => $order['trx_id'],
            'buyer_name' => $order['first_name'] . ' ' . $order['last_name']
        ]);
    }

    // LANGKAH 10: BATALKAN PESANAN

    public function cancel()
    {
        session()->remove('checkout_process');
        session()->remove('checkout_expire');
        session()->remove('pending_order_id');

        return redirect()->to('/')->with('warning', 'Pesanan berhasil dibatalkan.');
    }

    // PRIVATE HELPER FUNCTIONS

    private function checkSessionTime()
    {
        $session = session();

        if (!$session->has('checkout_process') || !$session->has('checkout_expire')) {
            return false;
        }

        $expireTime = $session->get('checkout_expire');
        $now = time();
        $timeLeft = $expireTime - $now;

        if ($timeLeft <= 0) {
            $session->remove('checkout_process');
            $session->remove('checkout_expire');
            return false;
        }

        return $timeLeft;
    }

    private function assignSeats($eventId, $ticketTypeId, $quantity)
    {
        $db = \Config\Database::connect();

        $takenSeatsQuery = $db->table('order_items')
            ->select('seat_id')
            ->join('orders', 'orders.id = order_items.order_id')
            ->whereIn('orders.status', ['pending', 'completed'])
            ->where('seat_id IS NOT NULL');

        $takenSeatsSql = $takenSeatsQuery->getCompiledSelect();

        $builder = $db->table('seats')
            ->where('event_id', $eventId)
            ->where('ticket_type_id', $ticketTypeId);

        if (!empty($takenSeatsSql)) {
            $builder->where("id NOT IN ($takenSeatsSql)", null, false);
        }

        $availableSeats = $builder->orderBy('id', 'ASC')
            ->limit($quantity)
            ->get()
            ->getResultArray();

        if (count($availableSeats) < $quantity) {
            return false; 
        }

        return array_column($availableSeats, 'id');
    }

    private function calculateTransaction($selectedTickets)
    {
        $ticketModel = new TicketTypeModel();
        
        $ticketTypes = $ticketModel->whereIn('id', array_keys($selectedTickets))->findAll();
        
        $items = [];
        $subTotal = 0;
        $totalQty = 0;

        foreach ($ticketTypes as $ticketType) {
            $qty = $selectedTickets[$ticketType['id']];
            
            if ($qty > 0) {
                $lineTotal = $ticketType['price'] * $qty;
                
                $items[] = [
                    'id' => $ticketType['id'],
                    'name' => $ticketType['name'],
                    'ticket_date' => $ticketType['ticket_date'],
                    'price' => $ticketType['price'],
                    'quantity' => $qty,
                    'subtotal' => $lineTotal
                ];
                
                $subTotal += $lineTotal;
                $totalQty += $qty;
            }
        }

        $taxRate = 0.10; // 10%
        $platformFeePerTicket = 10000; 
        $adminFee = 2500; 

        $taxAmount = $subTotal * $taxRate;
        $platformFeeTotal = $platformFeePerTicket * $totalQty;
        $grandTotal = $subTotal + $taxAmount + $platformFeeTotal + $adminFee;

        return [
            'items'         => $items,
            'sub_total'     => $subTotal,
            'tax_amount'    => $taxAmount,
            'platform_fee'  => $platformFeeTotal,
            'admin_fee'     => $adminFee,
            'grand_total'   => $grandTotal
        ];
    }
}