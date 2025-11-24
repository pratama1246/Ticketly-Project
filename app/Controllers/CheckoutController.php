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
    // Memulai Proses Checkout
    public function start()
    {
        // Ambil data tiket yang dipilih dari form POST
        $quantities = $this->request->getPost('quantity');
        $eventId = $this->request->getPost('eventId');

        if (empty($quantities) || empty($eventId)) {
            return redirect()->back()->with('error', 'Silakan pilih tiket terlebih dahulu.');
        }

        // Bersihkan data sampah (yang jumlahnya 0)
        $selectedTickets = array_filter($quantities, function ($qty) {
            return $qty > 0;
        });

        if (empty($selectedTickets)) {
            return redirect()->back()->with('error', 'Silakan pilih minimal 1 tiket.');
        }

        // Cek apakah user mencoba checkout event lain saat sesi masih aktif (Opsional)
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

        return redirect()->to('/checkout/personal_info');
    }

    // Langkah 2 (GET): Menampilkan Form Data Diri
    public function personalInfo()
    {
        $eventModel = new EventModel();
        $checkoutData = session('checkout_process');
        
        if (empty($checkoutData)) {
            return redirect()->to('/');
        }
        
        $data['event'] = $eventModel->find($checkoutData['event_id']);
        $data['step'] = 1;

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
            'birth_date'      => 'permit_empty|valid_date',
        ];

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
            'birth_date'      => $this->request->getPost('birth_date'),
        ];
        
        session()->set('checkout_process', $sessionData);
        return redirect()->to('/checkout/payment_method');
    }

    // Langkah 4 (GET): Menampilkan Halaman Metode Bayar
    public function paymentMethod()
    {
        $paymentModel = new \App\Models\PaymentMethodModel();
        
        // Ambil semua metode aktif
        $methods = $paymentModel->getActiveMethods();

        $data = [
            'step' => 2,
            // Filter E-Wallet
            'ewallets' => array_filter($methods, fn($m) => $m['type'] == 'ewallet'),
            // Filter Virtual Account
            'vas'      => array_filter($methods, fn($m) => $m['type'] == 'virtual_account'),
            // Filter Lainnya (Allo & Akulaku masuk sini)
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
            'payment_method' => 'required|string|in_list[ovo,dana,gopay,shopeepay,bca_va,bri_va,bni_va,qris]'
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
        $data['step'] = 3;

        // Mapping nama metode pembayaran untuk ditampilkan
        $paymentMethods = [
            'ovo' => 'OVO', 'dana' => 'DANA', 'gopay' => 'GoPay', 'shopeepay' => 'ShopeePay',
            'bca' => 'BCA Virtual Account', 'bri' => 'BRI Virtual Account', 'bni' => 'BNI Virtual Account',
            ''
        ];
        $data['payment_method_name'] = $paymentMethods[$checkoutData['payment_method']] ?? 'Tidak Dikenal';
        
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

        echo view('layout/header_checkout', $data);
        echo view('checkout_review_order', $data);
        echo view('layout/footer');
    }

    // Langkah 7 (POST): Submit Order, BUAT PDF, dan KIRIM EMAIL
   // ------------------------------------------------------------------
    // GANTI FUNGSI submitOrder() DENGAN 2 FUNGSI DI BAWAH INI
    // ------------------------------------------------------------------

    // TAHAP 1: Simpan Order (Status Pending) -> Arahkan ke Halaman Bayar
   // TAHAP 1: Simpan Order (Status Pending) -> Arahkan ke Halaman Bayar
    public function createOrder()
    {
        $session = session();
        $checkoutData = $session->get('checkout_process');

        if (empty($checkoutData)) { return redirect()->to('/'); }

        $orderModel = new OrderModel();
        $orderItemsModel = new OrderItemsModel();
        $ticketTypeModel = new TicketTypeModel();
        
        // Hitung Total & Validasi Stok
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

        // ============================================================
        // 2. GENERATE TRX ID (SESUAI REQUEST)
        // ============================================================
        // Format: TRX + Tanggal(Ymd) + 8 Karakter Hex Acak
        // Contoh: TRX-20251122-A1B2C3D4
        $randomStr = strtoupper(bin2hex(random_bytes(4))); 
        $trxId = 'TRX-' . date('Ymd') . '-' . $randomStr;

        // Mulai Database Transaction
        $db = \Config\Database::connect();
        $db->transStart();

        $personalData = $checkoutData['personal_data'];
        
        // Simpan Order (STATUS: PENDING)
        $orderData = [
            'user_id'         => auth()->loggedIn() ? auth()->id() : null,
            
            'trx_id'          => $trxId, // <--- MASUKKAN ID KEREN DI SINI
            
            'first_name'      => $personalData['first_name'],
            'last_name'       => $personalData['last_name'],
            'email'           => $personalData['email'],
            'phone_number'    => $personalData['phone_number'],
            'identity_number' => $personalData['identity_number'],
            'birth_date'      => $personalData['birth_date'],
            'payment_method'  => $checkoutData['payment_method'],
            'order_total'     => $totalPrice,
            'status'          => 'pending' 
        ];
        $orderModel->insert($orderData);
        $newOrderId = $orderModel->getInsertID();

        // Simpan Item Tiket
        foreach ($ticketDetails as $ticket) {
            // Generate Kode Tiket Unik
            $randTicket = strtoupper(bin2hex(random_bytes(3))); 
            $ticketUniqueCode = sprintf('TKT-E%02d-%d-%s', $checkoutData['event_id'], $newOrderId, $randTicket);

            $orderItemsModel->insert([
                'order_id'       => $newOrderId,
                'ticket_type_id' => $ticket['id'],
                'quantity'       => $ticket['quantity'],
                'price_per_ticket' => $ticket['price'],
                'ticket_code'    => $ticketUniqueCode
            ]);

            // Kurangi Stok
            $ticketTypeModel->where('id', $ticket['id'])
                            ->set('quantity_sold', 'quantity_sold + ' . $ticket['quantity'], false)
                            ->update();
        }
        
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/checkout/review_order')->with('error', 'Gagal membuat pesanan.');
        }

        $session->remove('checkout_process');
        $session->remove('checkout_time_left');

        // Redirect ke Halaman Bayar
        return redirect()->to('/checkout/pay/' . $newOrderId);
    }

    // TAHAP 2: Konfirmasi Bayar -> Update Status -> Kirim Email
    public function confirmPayment($orderId)
    {
        $orderModel = new OrderModel();
        $orderItemsModel = new OrderItemsModel();
        $eventModel = new EventModel();
        $ticketTypeModel = new TicketTypeModel();

        $order = $orderModel->find($orderId);
        if (!$order || $order['status'] == 'completed') {
            return redirect()->to('/');
        }

        // 1. Update Status Jadi Completed
        $orderModel->update($orderId, ['status' => 'completed']);

        // 2. Siapkan Data untuk PDF & Email (Ambil ulang dari DB)
        $items = $orderItemsModel->where('order_id', $orderId)->findAll();
        $firstItem = $items[0];
        $ticketType = $ticketTypeModel->find($firstItem['ticket_type_id']);
        $event = $eventModel->find($ticketType['event_id']);
        
        // Generate QR Code
        $qrContent = !empty($firstItem['ticket_code']) ? $firstItem['ticket_code'] : 'INVALID-CODE-' . $orderId;

        $qrCode = new QrCodeGenerator();
        $qrCodeBase64 = base64_encode($qrCode->format('png')->size(150)->generate($qrContent));

        $pdfData = [
            'eventName'     => $event['name'],
            'buyerName'     => $order['first_name'] . ' ' . $order['last_name'],
            'eventDate'     => (new \DateTime($event['event_date']))->format('d F Y, H:i') . ' WIB',
            'venue'         => $event['venue'],
            'ticketType'    => $ticketType['name'],
            'quantity'      => count($items), // Simplifikasi
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

        // 5. Redirect ke Sukses
        session()->setFlashdata('success_order_id', $orderId);
        return redirect()->to('/order/success');
    }
    
    public function pay($orderId)
    {
        $orderModel = new OrderModel();
        $eventModel = new EventModel();
        $orderItemsModel = new OrderItemsModel();

        // Ambil data order
        $order = $orderModel->find($orderId);

        if (!$order) {
            return redirect()->to('/');
        }

        // Ambil Event ID dari salah satu item tiket
        $item = $orderItemsModel->where('order_id', $orderId)->first();
        $ticketTypeModel = new TicketTypeModel();
        $ticket = $ticketTypeModel->find($item['ticket_type_id']);
        $event = $eventModel->find($ticket['event_id']);

        $data = [
            'title' => 'Menunggu Pembayaran',
            'order' => $order,
            'event' => $event,
            'step'  => 4
        ];

        echo view('layout/header_checkout', $data); // Pakai header checkout
        echo view('checkout_pay', $data);
        echo view('layout/footer');
    }

    // langkah 8 Halaman Sukses Pesanan
    public function orderSuccess()
    {
        $orderId = session()->getFlashdata('success_order_id');

        if (empty($orderId)) {
            return redirect()->to('/');
        }

        $orderModel = new OrderModel();
        $data['order'] = $orderModel->find($orderId);

        // Jika order tidak ditemukan
        if (empty($data['order'])) {
            return redirect()->to('/');
        }
        
        // Kirim data tambahan ke view
        $data['email'] = $data['order']['email'];

        echo view('layout/header');
        echo view('checkout_success', $data);
        echo view('layout/footer');
    }

    // Langkah 9 Halaman Timeout Checkout
    public function timeout()
    {
        echo view('layout/header');
        echo view('checkout_timeout');
        echo view('layout/footer');
    }

    // Langkah 10 Batalkan Checkout
    public function cancel()
    {
        session()->remove('checkout_process');
        session()->remove('checkout_time_left');
        return redirect()->to('/');
    }
}