<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\TicketTypeModel;
use App\Models\OrderModel;
use App\Models\OrderItemsModel;

use Dompdf\Dompdf;
use SimpleSoftwareIO\QrCode\Generator as QrCodeGenerator;

class CheckoutController extends BaseController
{
    // Langkah 1: Memulai Proses Checkout
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

        session()->set('checkout_expire', time() + 300);

        return redirect()->to('/checkout/personal_info');
    }

    // Langkah 2 (GET): Menampilkan Form Data Diri
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

    // Langkah 3 (POST): Memproses Form Data Diri
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

    // Langkah 4 (GET): Menampilkan Halaman Metode Bayar
    public function paymentMethod()
    {
        if (session()->has('pending_order_id')) {
            return redirect()->to('/checkout/pay/' . session()->get('pending_order_id'));
        }

        $timeLeft = $this->checkSessionTime();
        if ($timeLeft === false) { return redirect()->to('/'); }

        $paymentModel = new \App\Models\PaymentMethodModel();
        
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

    
    // Langkah 5 (POST): Memproses Metode Bayar
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


    // Langkah 6 (GET): Menampilkan Halaman Review
    public function reviewOrder()
    {
        $timeLeft = $this->checkSessionTime();
        if ($timeLeft === false) { return redirect()->to('/'); }

        $session = session();
        $checkoutData = $session->get('checkout_process');

        // ... (Validasi data diri & payment method biarkan sama) ...
        if (empty($checkoutData['personal_data']) || empty($checkoutData['payment_method'])) {
            return redirect()->to('/checkout/personal_info');
        }

        $eventModel = new EventModel();
        $ticketModel = new TicketTypeModel();

        $data['event'] = $eventModel->find($checkoutData['event_id']);
        $data['personal'] = $checkoutData['personal_data'];
        $data['payment_method'] = $checkoutData['payment_method'];
        
        // Ambil nama metode bayar
        $paymentModel = new \App\Models\PaymentMethodModel();
        $method = $paymentModel->where('code', $checkoutData['payment_method'])->first();
        $data['payment_method_name'] = $method ? $method['name'] : $checkoutData['payment_method'];
        
        // --- HITUNG RINCIAN BIAYA ---
        $tickets = [];
        $subTotal = 0;
        $totalQty = 0;
        
        $ticketTypes = $ticketModel->whereIn('id', array_keys($checkoutData['selected_tickets']))->findAll();

        foreach ($ticketTypes as $ticketType) {
            $quantity = $checkoutData['selected_tickets'][$ticketType['id']];
            $lineTotal = $ticketType['price'] * $quantity;
            
            $tickets[] = [
                'name' => $ticketType['name'],
                'ticket_date' => $ticketType['ticket_date'],
                'quantity' => $quantity,
                'price' => $ticketType['price'],
                'subtotal' => $lineTotal
            ];
            $subTotal += $lineTotal;
            $totalQty += $quantity;
        }

        // Rumus Biaya (Configurable)
        $taxRate = 0.10; // 10%
        $platformFeePerTicket = 10000; 
        $adminFee = 2500; 

        $taxAmount = $subTotal * $taxRate;
        $platformFeeTotal = $platformFeePerTicket * $totalQty;
        $grandTotal = $subTotal + $taxAmount + $platformFeeTotal + $adminFee;

        // Kirim Data ke View
        $data['selected_tickets_details'] = $tickets;
        $data['sub_total'] = $subTotal;
        $data['tax_amount'] = $taxAmount;
        $data['platform_fee'] = $platformFeeTotal;
        $data['admin_fee'] = $adminFee;
        $data['grand_total'] = $grandTotal;

        $data['step'] = 3;
        $data['time_left'] = $timeLeft;

        echo view('layout/header_checkout', $data);
        echo view('checkout_review_order', $data); // View yang akan kita edit
        echo view('layout/footer');
    }

    // Langkah 7: Simpan dan Buat Order
   public function createOrder()
    {
        // ... (Validasi session awal biarkan sama) ...
        $session = session();
        $checkoutData = $session->get('checkout_process');
        if (empty($checkoutData)) { return redirect()->to('/'); }

        $orderModel = new OrderModel();
        $orderItemsModel = new OrderItemsModel();
        $ticketTypeModel = new TicketTypeModel();
        
        // 1. Hitung Ulang Total (Sama persis kayak di reviewOrder)
        $subTotal = 0;
        $totalQty = 0;
        $ticketDetails = [];
        $ticketTypes = $ticketTypeModel->whereIn('id', array_keys($checkoutData['selected_tickets']))->findAll();

        foreach ($ticketTypes as $ticketType) {
            $quantity = $checkoutData['selected_tickets'][$ticketType['id']];
            
            // Cek Stok Lagi (Optional tapi Recommended)
            if ($ticketType['quantity_sold'] + $quantity > $ticketType['quantity_total']) {
                return redirect()->back()->with('error', 'Stok tiket ' . $ticketType['name'] . ' tidak mencukupi.');
            }

            $lineTotal = $ticketType['price'] * $quantity;
            $ticketDetails[] = [
                'id' => $ticketType['id'], 
                'price' => $ticketType['price'], 
                'quantity' => $quantity
            ];
            $subTotal += $lineTotal;
            $totalQty += $quantity;
        }

        $taxAmount = $subTotal * 0.10;
        $platformFeeTotal = 10000 * $totalQty;
        $adminFee = 2500;
        $grandTotal = $subTotal + $taxAmount + $platformFeeTotal + $adminFee;

        // ... (Generate TRX ID biarkan sama) ...
        $randomStr = strtoupper(bin2hex(random_bytes(4))); 
        $trxId = 'TRX-' . date('Ymd') . '-' . $randomStr;

        $db = \Config\Database::connect();
        $db->transStart();

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
            'order_total'     => $grandTotal, // TOTAL YANG BARU
            'status'          => 'Pending'
        ];
        
        
        $orderModel->insert($orderData);
        $newOrderId = $orderModel->getInsertID();

        foreach ($ticketDetails as $ticket) {
            $ticketInfo = $ticketTypeModel->find($ticket['id']);
            $isSeating = ($ticketInfo['ticket_category'] === 'Seating');

            $assignedSeatId = [];

            if ($isSeating) {
                $assignedSeatId = $this->assignSeats($checkoutData['event_id'], $ticket['id'], $ticket['quantity']);

                // Cek Ketersediaan Kursi
                if ($assignedSeatId === false) {
                    $db->transRollback();
                    return redirect()->to('/checkout/review_order')
                     ->with('error', 'Mohon maaf, kursi untuk tiket ' . $ticketInfo['name'] . ' baru saja habis atau sedang dipesan orang lain. Silakan pilih tiket lain atau coba lagi.');
                }        
            }

            for ($i = 0; $i < $ticket['quantity']; $i++) {

            // Generate Ticket Code Unik
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

            // Potong Stok
            $ticketTypeModel->where('id', $ticket['id'])
                            ->set('quantity_sold', 'quantity_sold + ' . $ticket['quantity'], false)
                            ->update();
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/checkout/review_order')->with('error', 'Gagal membuat pesanan.');
        }

        // Set Session Akhir
        $session->remove('checkout_process');
        $session->remove('checkout_expire');
        $session->set('pending_order_id', $newOrderId);

        return redirect()->to('/checkout/pay/' . $newOrderId);
    }

   // Langkah 8: Konfirmasi Pembayaran dan Kirim E-Ticket
    public function confirmPayment($orderId)
    {   
        if (!$this->request->isAJAX()) {
            return redirect()->to('/');
        }

        $orderModel = new OrderModel();
        $orderItemsModel = new OrderItemsModel();
        $eventModel = new EventModel();
        $ticketTypeModel = new TicketTypeModel();

        $order = $orderModel->find($orderId);
        if (!$order) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Pesanan tidak ditemukan.']);
        }

        if ($order['status'] == 'completed') {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Pesanan sudah dikonfirmasi sebelumnya.']);
        }

        // Update status order
        $orderModel->update($orderId, ['status' => 'completed']);

        // --- UPDATE PENTING DISINI ---
        // Kita join ke tabel 'seats' buat ambil nama kursinya (misal: A12, B05)
        // Asumsi nama kolom di tabel seats adalah 'name' atau 'label'. Sesuaikan ya!
        $items = $orderItemsModel->select('order_items.*, seats.name as seat_name') 
            ->join('seats', 'seats.id = order_items.seat_id', 'left')
            ->where('order_id', $orderId)
            ->findAll();

        $firstItem = $items[0];
        $ticketType = $ticketTypeModel->find($firstItem['ticket_type_id']);
        $event = $eventModel->find($ticketType['event_id']);

        // Logic gabungin nomor kursi kalau beli banyak (misal: A1, A2, A3)
        $seatList = [];
        foreach ($items as $item) {
            if (!empty($item['seat_name'])) {
                $seatList[] = $item['seat_name'];
            }
        }
        // Kalau seating, gabung pake koma. Kalau festival, strip aja.
        $seatString = !empty($seatList) ? implode(', ', $seatList) : '-';

        $qrContent = !empty($firstItem['ticket_code']) ? $firstItem['ticket_code'] : 'INVALID-CODE-' . $orderId;

        $qrCode = new QrCodeGenerator();
        $qrCodeBase64 = base64_encode($qrCode->format('png')->size(150)->generate($qrContent));

        $pdfData = [
            'eventName'     => $event['name'],
            'buyerName'     => $order['first_name'] . ' ' . $order['last_name'],
            'eventDate'     => (new \DateTime($event['event_date']))->format('Y-m-d H:i:s'), // Format standard biar bisa diolah view
            'venue'         => $event['venue'],
            'ticketType'    => $ticketType['name'],
            'quantity'      => count($items), 
            'orderId'       => $order['trx_id'], // Pake Trx ID biar lebih pro daripada ID auto increment
            'qrCodeBase64'  => $qrCodeBase64,
            'ticketCode'    => $firstItem['ticket_code'],
            'seatNumber'    => $seatString // <--- INI VARIABEL BARUNYA
        ];

        // 3. Buat PDF
        $html = view('ticket_template', $pdfData);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfOutput = $dompdf->output();

        // 4. Kirim Email
        $email = \Config\Services::email();
        $email->setTo($order['email']);
        $email->setSubject('E-Tiket Anda untuk ' . $event['name']);
        
        // Pake template view buat body email biar rapi juga (Optional, bisa pake string biasa)
        $email->setMessage("Halo kak {$order['first_name']}, Terima kasih sudah memesan! Tiket terlampir di PDF ya. Sampai jumpa di venue!");
        
        $email->attach($pdfOutput, 'attachment', 'E-Ticket-' . $order['trx_id'] . '.pdf', 'application/pdf');
        
        if ($email->send()) {
            session()->remove('checkout_process');
            session()->remove('checkout_expire');
            session()->remove('pending_order_id');
            return $this->response->setJSON(['status' => 'success', 'email' => $order['email'], 'trx_id' => $order['trx_id']]);
        } else {
             return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengirim email, tapi order sukses.']);
        }
    }

    // Langkah 9: Halaman Pembayaran
    public function pay($orderId)
    {
        $orderModel = new OrderModel();
        $eventModel = new EventModel();
        $orderItemsModel = new OrderItemsModel();

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
        $ticketTypeModel = new TicketTypeModel();
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

    // Langkah 12 Batalkan Checkout
    public function cancel()
    {
        session()->remove('checkout_process');
        session()->remove('checkout_expire');
        session()->remove('pending_order_id');

        return redirect()->to('/')->with('warning', 'Pesanan berhasil dibatalkan.');
    }

    // Fungsi Cek Waktu Sesi Checkout
    private function checkSessionTime()
    {
        $session = session();

        if (!$session->has('checkout_process')) {
            return false;
        }

        if (!$session->has('checkout_expire')) {
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

    /**
     * Mencari kursi yang tersedia secara otomatis.
     *
     * @param int $eventId
     * @param int $ticketTypeId
     * @param int $quantity
     * @return array|false Daftar ID kursi atau false jika tidak cukup
     */

    private function assignSeats($eventId, $ticketTypeId, $quantity)
    {
        $db = \Config\Database::connect();

        $takenSeatsQuery = $db->table('order_items')
            ->select('seat_id')
            ->join('orders', 'orders.id = order_items.order_id')
            ->whereIn('orders.status', ['pending', 'completed'])
            ->where('seat_id IS NOT NULL');

        $takenSeatsSql = $takenSeatsQuery->getCompiledSelect();

        $availableSeats = $db->table('seats')
            ->where('event_id', $eventId)
            ->where('ticket_type_id', $ticketTypeId)
            ->where("id NOT IN ($takenSeatsSql)", null, false)
            ->orderBy('id', 'ASC')
            ->limit($quantity)
            ->get()
            ->getResultArray();

        if (count($availableSeats) < $quantity) {
            return false; // Kursi gak cukup!
        }

            // Ambil kolom 'id'-nya saja
            return array_column($availableSeats, 'id');
    }
}