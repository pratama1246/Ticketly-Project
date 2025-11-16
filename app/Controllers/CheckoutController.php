<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\TicketTypeModel;
use App\Models\OrderModel;
use App\Models\OrderItemsModel;

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

        $totalPrice = 0;
        $ticketDetails = [];
        $ticketTypes = $ticketTypeModel->whereIn('id', array_keys($checkoutData['selected_tickets']))
                                       ->findAll();

        foreach ($ticketTypes as $ticketType) {
            $quantity = $checkoutData['selected_tickets'][$ticketType['id']];
            if ($quantity > ($ticketType['quantity_total'] - $ticketType['quantity_sold'])) {
                return redirect()->to('/checkout/review_order')->with('error', 'Maaf, stok untuk ' . $ticketType['name'] . ' tidak mencukupi.');
            }
            $subtotal = $ticketType['price'] * $quantity;
            $ticketDetails[] = [
                'id'       => $ticketType['id'],
                'quantity' => $quantity,
                'price'    => $ticketType['price']
            ];
            $totalPrice += $subtotal;
        }

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
        $newOrderId = $orderModel->getInsertID();

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

        if ($db->transStatus() === false) {
            return redirect()->to('/checkout/review_order')->with('error', 'Terjadi kesalahan saat memproses pesanan Anda. Silakan coba lagi.');
        }

        $session->remove('checkout_process');
        $session->remove('checkout_time_left');
        $session->setFlashdata('success_order_id', $newOrderId);
        
        return redirect()->to('/order/success');
    }
    
    /**
     * Halaman Sukses (setelah submit)
     */
    public function orderSuccess()
    {
        $orderId = session()->getFlashdata('success_order_id');

        if (empty($orderId)) {
            return redirect()->to('/');
        }

        $orderModel = new OrderModel();
        $data['order'] = $orderModel->find($orderId);

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
}