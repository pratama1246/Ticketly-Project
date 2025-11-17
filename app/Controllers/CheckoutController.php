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
    /**
     * Langkah 1: Memulai Checkout
     */
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

    /**
     * Langkah 2 (GET): Menampilkan Form Data Diri
     */
    public function personalInfo()
    {
        $eventModel = new EventModel();
        $checkoutData = session('checkout_process');
        
        if (empty($checkoutData)) {
            return redirect()->to('/');
        }
        
        $data['event'] = $eventModel->find($checkoutData['event_id']);
        $data['step'] = 1;

        echo view('layout/header', $data);
        echo view('checkout_personal_info', $data); 
        echo view('layout/footer');
    }

    /**
     * Langkah 2.5 (POST): Memproses Form Data Diri
     */
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

    /**
     * Langkah 3 (GET): Menampilkan Halaman Metode Bayar
     */
    public function paymentMethod()
    {
        $data['step'] = 2;

        echo view('layout/header', $data);
        echo view('checkout_payment_method', $data);
        echo view('layout/footer');
    }

    /**
     * Langkah 3.5 (POST): Memproses Metode Bayar
     */
    public function processPayment()
    {
        $rules = [
            'payment_method' => 'required|string|in_list[ovo,dana,gopay,shopeepay,bca_va,bri_va,bni_va]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Silakan pilih satu metode pembayaran.');
        }

        $sessionData = session('checkout_process');
        $sessionData['payment_method'] = $this->request->getPost('payment_method');
        session()->set('checkout_process', $sessionData);

        return redirect()->to('/checkout/review_order');
    }

    /**
     * Langkah 4 (GET): Menampilkan Halaman Review
     */
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

        $paymentMethods = [
            'ovo' => 'OVO', 'dana' => 'DANA', 'gopay' => 'GoPay', 'shopeepay' => 'ShopeePay',
            'bca_va' => 'BCA Virtual Account', 'bri_va' => 'BRI Virtual Account', 'bni_va' => 'BNI Virtual Account'
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

        echo view('layout/header', $data);
        echo view('checkout_review_order', $data);
        echo view('layout/footer');
    }

    /**
     * Langkah 5 (POST): Submit Order ke Database
     */
    /**
     * Langkah 5 (POST): Submit Order, BUAT PDF, dan KIRIM EMAIL
     */
    public function submitOrder()
    {
        $session = session();
        $checkoutData = $session->get('checkout_process');

        if (empty($checkoutData)) {
            return redirect()->to('/');
        }

        $orderModel = new OrderModel();
        $orderItemsModel = new OrderItemsModel();
        $ticketTypeModel = new TicketTypeModel();
        $eventModel = new EventModel(); // Kita butuh ini untuk info event

        // --- 1. Validasi Data & Hitung Total (Sama seperti sebelumnya) ---
        $totalPrice = 0;
        $ticketDetails = [];
        $ticketTypes = $ticketTypeModel->whereIn('id', array_keys($checkoutData['selected_tickets']))
                                       ->findAll();
        
        // Ambil info event
        $event = $eventModel->find($checkoutData['event_id']);

        foreach ($ticketTypes as $ticketType) {
            $quantity = $checkoutData['selected_tickets'][$ticketType['id']];
            if ($quantity > ($ticketType['quantity_total'] - $ticketType['quantity_sold'])) {
                return redirect()->to('/checkout/review_order')->with('error', 'Maaf, stok untuk ' . $ticketType['name'] . ' tidak mencukupi.');
            }
            $subtotal = $ticketType['price'] * $quantity;
            $ticketDetails[] = [
                'id'       => $ticketType['id'],
                'name'     => $ticketType['name'], // Nama tiket untuk PDF
                'quantity' => $quantity,
                'price'    => $ticketType['price']
            ];
            $totalPrice += $subtotal;
        }

        // --- 2. Mulai Transaksi Database (Sama seperti sebelumnya) ---
        $db = \Config\Database::connect();
        $db->transStart();

        $personalData = $checkoutData['personal_data'];
        $orderData = [
            'user_id'         => auth()->loggedIn() ? auth()->id() : null,
            'first_name'      => $personalData['first_name'],
            'last_name'       => $personalData['last_name'],
            'email'           => $personalData['email'],
            'phone_number'    => $personalData['phone_number'],
            'identity_number' => $personalData['identity_number'],
            'birth_date'      => $personalData['birth_date'],
            'order_total'     => $totalPrice,
            'status'          => 'completed' 
        ];
        $orderModel->insert($orderData);
        $newOrderId = $orderModel->getInsertID(); // Ambil ID pesanan baru

        foreach ($ticketDetails as $ticket) {
            $itemData = [
                'order_id'       => $newOrderId,
                'ticket_type_id' => $ticket['id'],
                'quantity'       => $ticket['quantity'],
                'price_per_ticket' => $ticket['price']
            ];
            $orderItemsModel->insert($itemData);

            $ticketTypeModel->where('id', $ticket['id'])
                            ->set('quantity_sold', 'quantity_sold + ' . $ticket['quantity'], false)
                            ->update();
        }
        
        $db->transComplete();

        // --- 3. Cek Transaksi Gagal (Sama seperti sebelumnya) ---
        if ($db->transStatus() === false) {
            return redirect()->to('/checkout/review_order')->with('error', 'Terjadi kesalahan saat memproses pesanan Anda. Silakan coba lagi.');
        }

        // ======================================================
        // LANGKAH BARU: BUAT PDF & KIRIM EMAIL
        // ======================================================
        
        // --- 4. Siapkan Data untuk PDF & Email ---
        
        // Kita hanya akan membuat 1 PDF gabungan untuk 1 TIPE tiket
        // (Jika beli 2 VIP, jadi 1 PDF untuk 2 tiket VIP)
        // Kita ambil tiket pertama sebagai contoh
        $firstTicket = $ticketDetails[0];
        $ticketCode = 'T-' . $newOrderId . '-' . $firstTicket['id']; // Kode unik tiket

        // Generate QR Code
        $qrCode = new QrCodeGenerator();
        $qrCodeBase64 = base64_encode($qrCode->format('png')->size(150)->generate($ticketCode));

        // Format tanggal event
        $eventDate = new \DateTime($event['event_date']);

        $pdfData = [
            'eventName'     => $event['name'],
            'buyerName'     => $personalData['first_name'] . ' ' . $personalData['last_name'],
            'eventDate'     => $eventDate->format('d F Y, H:i') . ' WIB',
            'venue'         => $event['venue'],
            'ticketType'    => $firstTicket['name'],
            'quantity'      => $firstTicket['quantity'],
            'orderId'       => $newOrderId,
            'qrCodeBase64'  => $qrCodeBase64,
            'ticketCode'    => $ticketCode
        ];

        // --- 5. Render HTML Tiket ---
        $html = view('ticket_template', $pdfData);

        // --- 6. Buat PDF menggunakan Dompdf ---
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        // Ambil output PDF sebagai string
        $pdfOutput = $dompdf->output();
        
        // --- 7. Konfigurasi & Kirim Email ---
        $email = \Config\Services::email();
        
        // Ambil konfigurasi dari file .env
        // PASTIKAN kamu sudah setting ini di .env:
        // email.fromEmail = 'emailmu@gmail.com'
        // email.fromName = 'Ticketly'
        // email.SMTPHost = 'smtp.gmail.com'
        // email.SMTPUser = 'emailmu@gmail.com'
        // email.SMTPPass = 'password_app_gmailmu'
        // email.SMTPPort = 465
        // email.SMTPCrypto = 'ssl'
        
        $email->setTo($personalData['email']);
        $email->setSubject('E-Tiket Anda untuk ' . $event['name']);
        $email->setMessage('Terima kasih telah memesan! Terlampir adalah e-tiket Anda.');
        
        // Lampirkan PDF
        $email->attach($pdfOutput, 'attachment', 'E-Ticket-Order-'.$newOrderId.'.pdf', 'application/pdf');

        if (! $email->send()) {
            // Gagal kirim email, tapi transaksi SUDAH berhasil.
            // Kita bisa log error ini, tapi tetap lanjutkan.
            log_message('error', 'Gagal mengirim email tiket untuk order: ' . $newOrderId . ' - ' . $email->printDebugger(['headers']));
        }
        
        // ======================================================
        // AKHIR LANGKAH BARU
        // ======================================================

        // Hancurkan session checkout
        $session->remove('checkout_process');
        $session->remove('checkout_time_left');

        // Simpan order_id ke flash session untuk halaman sukses
        $session->setFlashdata('success_order_id', $newOrderId);
        
        return redirect()->to('/order/success');
    }
    
    /**
     * Halaman Sukses (setelah submit)
     */
    /**
     * Halaman yang ditampilkan setelah checkout berhasil
     */
    public function orderSuccess()
    {
        $orderId = session()->getFlashdata('success_order_id');

        if (empty($orderId)) {
            return redirect()->to('/');
        }

        $orderModel = new OrderModel();
        $data['order'] = $orderModel->find($orderId);

        // Jika order tidak ditemukan (misal pengguna refresh halaman)
        if (empty($data['order'])) {
            return redirect()->to('/');
        }
        
        // Kirim data tambahan ke view
        $data['email'] = $data['order']['email'];

        echo view('layout/header');
        echo view('checkout_success', $data);
        echo view('layout/footer');
    }

    /**
     * Halaman Waktu Habis (dari filter)
     */
    public function timeout()
    {
        echo view('layout/header');
        echo view('checkout_timeout');
        echo view('layout/footer');
    }

    public function cancel()
    {
        // Hancurkan semua data checkout dari session
        session()->remove('checkout_process');
        session()->remove('checkout_time_left');

        // Kembalikan pengguna ke halaman utama
        return redirect()->to('/');
    }
}