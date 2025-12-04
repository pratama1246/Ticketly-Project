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
        if (session()->has('pending_order_id')) {
            return redirect()->to('/checkout/pay/' . session()->get('pending_order_id'));
        }
        
        $timeLeft = $this->checkSessionTime();
        if ($timeLeft === false) { return redirect()->to('/'); }

        $session = session();
        $checkoutData = $session->get('checkout_process');

        if (empty($checkoutData['personal_data']) || empty($checkoutData['payment_method'])) {
            return redirect()->to('/checkout/personal_info');
        }

        $eventModel = new EventModel();
        $ticketModel = new TicketTypeModel();

        $data['event'] = $eventModel->find($checkoutData['event_id']);
        $data['personal'] = $checkoutData['personal_data'];
        $data['payment_method'] = $checkoutData['payment_method'];
        
        $paymentModel = new \App\Models\PaymentMethodModel();
        $method = $paymentModel->where('code', $checkoutData['payment_method'])->first();
        $data['payment_method_name'] = $method ? $method['name'] : $checkoutData['payment_method'];
        
        $tickets = [];
        $totalPrice = 0;
        
        $ticketTypes = $ticketModel->whereIn('id', array_keys($checkoutData['selected_tickets']))
                                   ->findAll();

        foreach ($ticketTypes as $ticketType) {
            $quantity = $checkoutData['selected_tickets'][$ticketType['id']];
            $subtotal = $ticketType['price'] * $quantity;
            $tickets[] = [
                'name' => $ticketType['name'],
                'quantity' => $quantity,
                'price' => $ticketType['price'],
                'subtotal' => $subtotal
            ];
            $totalPrice += $subtotal;
        }

        $data['selected_tickets_details'] = $tickets;
        $data['total_price'] = $totalPrice;
        $data['step'] = 3;
        $data['time_left'] = $timeLeft;

        echo view('layout/header_checkout', $data);
        echo view('checkout_review_order', $data);
        echo view('layout/footer');
    }

    // Langkah 7: Simpan dan Buat Order
    public function createOrder()
    {
        $session = session();
        $checkoutData = $session->get('checkout_process');

        if (empty($checkoutData)) { return redirect()->to('/'); }

        $orderModel = new OrderModel();
        $orderItemsModel = new OrderItemsModel();
        $ticketTypeModel = new TicketTypeModel();
        
        $totalPrice = 0;
        $ticketDetails = [];
        $ticketTypes = $ticketTypeModel->whereIn('id', array_keys($checkoutData['selected_tickets']))->findAll();

        foreach ($ticketTypes as $ticketType) {
            $quantity = $checkoutData['selected_tickets'][$ticketType['id']];
            if ($quantity > ($ticketType['quantity_total'] - $ticketType['quantity_sold'])) {
                return redirect()->to('/checkout/review_order')->with('error', 'Stok habis.');
            }
            $subtotal = $ticketType['price'] * $quantity;
            $ticketDetails[] = [
                'id' => $ticketType['id'], 'price' => $ticketType['price'], 'quantity' => $quantity
            ];
            $totalPrice += $subtotal;
        }

        // 2. GENERATE TRX ID
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
            'order_total'     => $totalPrice,
            'status'          => 'Pending' 
        ];
        $orderModel->insert($orderData);
        $newOrderId = $orderModel->getInsertID();

        foreach ($ticketDetails as $ticket) {
            $randTicket = strtoupper(bin2hex(random_bytes(3))); 
            $ticketUniqueCode = sprintf('TKT-E%02d-%d-%s', $checkoutData['event_id'], $newOrderId, $randTicket);

            $orderItemsModel->insert([
                'order_id'       => $newOrderId,
                'ticket_type_id' => $ticket['id'],
                'quantity'       => $ticket['quantity'],
                'price_per_ticket' => $ticket['price'],
                'ticket_code'    => $ticketUniqueCode
            ]);

            $ticketTypeModel->where('id', $ticket['id'])
                            ->set('quantity_sold', 'quantity_sold + ' . $ticket['quantity'], false)
                            ->update();
        }
        
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/checkout/review_order')->with('error', 'Gagal membuat pesanan.');
        }

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

        $orderModel->update($orderId, ['status' => 'completed']);

        $items = $orderItemsModel->where('order_id', $orderId)->findAll();
        $firstItem = $items[0];
        $ticketType = $ticketTypeModel->find($firstItem['ticket_type_id']);
        $event = $eventModel->find($ticketType['event_id']);

        $qrContent = !empty($firstItem['ticket_code']) ? $firstItem['ticket_code'] : 'INVALID-CODE-' . $orderId;

        $qrCode = new QrCodeGenerator();
        $qrCodeBase64 = base64_encode($qrCode->format('png')->size(150)->generate($qrContent));

        $pdfData = [
            'eventName'     => $event['name'],
            'buyerName'     => $order['first_name'] . ' ' . $order['last_name'],
            'eventDate'     => (new \DateTime($event['event_date']))->format('d F Y, H:i') . ' WIB',
            'venue'         => $event['venue'],
            'ticketType'    => $ticketType['name'],
            'quantity'      => count($items), 
            'orderId'       => '#' . $orderId,
            'qrCodeBase64'  => $qrCodeBase64,
            'ticketCode'    => $firstItem['ticket_code']
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
        $email->setMessage('Pembayaran berhasil! Berikut adalah tiket Anda.');
        $email->attach($pdfOutput, 'attachment', 'E-Ticket-' . $orderId . '.pdf', 'application/pdf');
        $email->send();

        session()->remove('checkout_process');
        session()->remove('checkout_expire');
        session()->remove('pending_order_id');

        return $this->response->setJSON(['status' => 'success', 'email' => $order['email'], 'trx_id' => $order['trx_id']]);
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
}