<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemsModel;
use App\Models\EventModel;
use Dompdf\Dompdf;
use SimpleSoftwareIO\QrCode\Generator as QrCodeGenerator;

class OrderController extends BaseController
{
    protected $orderModel;
    protected $orderItemsModel;
    protected $eventModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderItemsModel = new OrderItemsModel();
        $this->eventModel = new EventModel();
    }

    // 1. LIST ORDER (RIWAYAT)
    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');

        $builder = $this->orderModel->orderBy('created_at', 'DESC');

        if ($keyword) {
            $builder->groupStart()
                ->like('trx_id', $keyword)
                ->orLike('email', $keyword)
                ->orLike('first_name', $keyword)
                ->groupEnd();
        }

        if ($status) {
            $builder->where('status', $status);
        }

        $data = [
            'title'  => 'Manajemen Order',
            'orders' => $builder->paginate(10),
            'pager'  => $this->orderModel->pager,
            'keyword' => $keyword,
            'status' => $status
        ];

        echo view('admin/layout/header', $data);
        echo view('admin/orders/index', $data);
        echo view('admin/layout/footer');
    }

    // 2. DETAIL ORDER
    public function detail($id)
    {
        $order = $this->orderModel->find($id);
        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Order tidak ditemukan');
        }

        // Ambil item + info tiket + info event + info kursi
        $items = $this->orderItemsModel->select('order_items.*, ticket_types.name as ticket_name, events.name as event_name, seats.label, seats.seat_row, seats.seat_number')
            ->join('ticket_types', 'ticket_types.id = order_items.ticket_type_id', 'left')
            ->join('events', 'events.id = ticket_types.event_id', 'left') // Join ke event via ticket type
            ->join('seats', 'seats.id = order_items.seat_id', 'left')
            ->where('order_id', $id)
            ->findAll();

        $data = [
            'title' => 'Detail Order #' . $order['trx_id'],
            'order' => $order,
            'items' => $items
        ];

        echo view('admin/layout/header', $data);
        echo view('admin/orders/detail', $data);
        echo view('admin/layout/footer');
    }

    // 3. UPDATE STATUS (Fitur Tambahan)
    public function updateStatus()
    {
        $id = $this->request->getPost('order_id');
        $status = $this->request->getPost('status');

        if ($this->orderModel->update($id, ['status' => $status])) {
            return redirect()->back()->with('success', 'Status order berhasil diperbarui.');
        }
        return redirect()->back()->with('error', 'Gagal update status.');
    }

    // 4. DOWNLOAD PDF (Admin Privilege)
    public function downloadPdf($orderId)
    {
        $order = $this->orderModel->find($orderId);
        if (!$order) return redirect()->back()->with('error', 'Order not found');

        // Logic sama persis kayak CheckoutController
        $items = $this->orderItemsModel->select('order_items.*, seats.label, seats.seat_row, seats.seat_number, ticket_types.name as ticket_name, ticket_types.event_id') 
            ->join('seats', 'seats.id = order_items.seat_id', 'left')
            ->join('ticket_types', 'ticket_types.id = order_items.ticket_type_id', 'left')
            ->where('order_id', $orderId)
            ->findAll();

        if (empty($items)) return redirect()->back()->with('error', 'Item kosong');

        $event = $this->eventModel->find($items[0]['event_id']);
        
        $ticketList = [];
        $qrCode = new QrCodeGenerator();

        foreach ($items as $item) {
            $seatLabel = 'Free Seating'; 
            if (!empty($item['label'])) $seatLabel = $item['label']; 
            elseif (!empty($item['seat_row'])) $seatLabel = $item['seat_row'] . '-' . $item['seat_number'];

            $qrContent = !empty($item['ticket_code']) ? $item['ticket_code'] : 'ERR-' . $item['id'];
            $qrBase64 = base64_encode($qrCode->format('png')->size(150)->generate($qrContent));

            $ticketList[] = [
                'type' => $item['ticket_name'],
                'seat' => $seatLabel,
                'code' => $item['ticket_code'],
                'qr'   => $qrBase64
            ];
        }

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('ticket_template', [
            'event' => $event,
            'order' => $order,
            'tickets' => $ticketList
        ]));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Langsung download ke browser admin
        $dompdf->stream('Admin-Copy-Ticket-' . $order['trx_id'] . '.pdf', ['Attachment' => 0]); 
    }
}