<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TicketTypeModel;
use App\Models\EventModel;

class TicketController extends BaseController
{
    protected $ticketModel;
    protected $eventModel;

    public function __construct()
    {
        $this->ticketModel = new TicketTypeModel();
        $this->eventModel = new EventModel();
    }

    // Menampilkan daftar tiket
    public function index($eventId)
    {
        $event = $this->eventModel->find($eventId);
        
        if (!$event) {
            return redirect()->to('/admin/events')->with('error', 'Event tidak ditemukan.');
        }

        $data = [
            'title'   => 'Kelola Tiket: ' . $event['name'],
            'event'   => $event,
            'tickets' => $this->ticketModel->where('event_id', $eventId)->findAll()
        ];

        echo view('admin/layout/header', $data);
        echo view('admin/tickets/index', $data); 
        echo view('admin/layout/footer');
    }

    // Form tambah tiket baru
    public function new($eventId)
    {
        $event = $this->eventModel->find($eventId);
        
        if (!$event) {
            return redirect()->to('/admin/events');
        }

        $data = [
            'title' => 'Tambah Tiket Baru',
            'event' => $event,
            'validation' => \Config\Services::validation()
        ];

        echo view('admin/layout/header', $data);
        echo view('admin/tickets/new', $data); 
        echo view('admin/layout/footer');
    }

    // Proses simpan tiket baru
   public function create($eventId)
    {
        // Validasi
        $rules = [
            'name'            => 'required|string|max_length[255]',
            'ticket_category' => 'required|in_list[Seating,Standing]', // Validasi Kategori
            'price'           => 'required|numeric',
            'quantity_total'  => 'required|integer',
            'ui_color'        => 'required|string|max_length[7]', // Validasi Hex Color
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $ticketDate = $this->request->getPost('ticket_date');

        $this->ticketModel->save([
            'event_id'        => $eventId,
            'name'            => $this->request->getPost('name'),
            'ticket_date'     => empty($ticketDate) ? null : $ticketDate,
            'ticket_category' => $this->request->getPost('ticket_category'), // Simpan Kategori
            'price'           => $this->request->getPost('price'),
            'quantity_total'  => $this->request->getPost('quantity_total'),
            'quantity_sold'   => 0,
            'ui_color'        => $this->request->getPost('ui_color'), // Simpan Hex
            'description'     => $this->request->getPost('description')
        ]);

    $newTicketId = $this->ticketModel->getInsertID(); // ID Tiket Baru

    // === LOGIC GENERATOR KURSI (BARU) ===
    $startRow = $this->request->getPost('seat_row_start');
    $endRow   = $this->request->getPost('seat_row_end');
    $perRow   = $this->request->getPost('seats_per_row');

    // Cek: Kalau admin ngisi generator, berarti ini tiket seating
    if (!empty($startRow) && !empty($endRow) && !empty($perRow)) {
        
        $seatData = [];
        $startCode = ord(strtoupper($startRow)); // A -> 65
        $endCode   = ord(strtoupper($endRow));   // E -> 69

        // Loop Baris (A sampai E)
        for ($rowCode = $startCode; $rowCode <= $endCode; $rowCode++) {
            $rowChar = chr($rowCode); // 65 -> A
            
            // Loop Nomor Kursi (1 sampai 20)
            for ($num = 1; $num <= $perRow; $num++) {
                $seatData[] = [
                    'event_id'       => $eventId,
                    'ticket_type_id' => $newTicketId,
                    'seat_row'       => $rowChar,
                    'seat_number'    => $num,
                    'label'          => $rowChar . '-' . $num, // Format: A-1
                ];
            }
        }

        // Masukin ke Database (Batch Insert biar cepet)
        if (!empty($seatData)) {
            $db = \Config\Database::connect();
            $db->table('seats')->insertBatch($seatData);
            
            // Opsional: Update tiket jadi kategori 'Seating' kalau belum
            $this->ticketModel->update($newTicketId, ['ticket_category' => 'Seating']);
        }
    }

        return redirect()->to("/admin/events/$eventId/tickets")->with('message', 'Tiket berhasil ditambahkan.');
    }

    // Proses hapus tiket
    public function delete($eventId, $ticketId)
    {
        $this->ticketModel->delete($ticketId);
        
        return $this->response->setJSON([
            'status' => 'success', 
            'message' => 'Tiket berhasil dihapus.'
        ]);
    }

    public function edit($eventId, $ticketId)
    {
        $event = $this->eventModel->find($eventId);
        $ticket = $this->ticketModel->find($ticketId);

        if (!$event || !$ticket) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Tiket: ' . $ticket['name'],
            'event' => $event,
            'ticket' => $ticket, // Kirim data tiket ke view
            'validation' => \Config\Services::validation()
        ];

        echo view('admin/layout/header', $data);
        echo view('admin/tickets/edit', $data); // Kita buat file ini sebentar lagi
        echo view('admin/layout/footer');
    }

    // Proses update tiket
    public function update($eventId, $ticketId)
    {
        $rules = [
            'name'            => 'required|string|max_length[255]',
            'ticket_category' => 'required|in_list[Seating,Standing]',
            'price'           => 'required|numeric',
            'quantity_total'  => 'required|integer',
            'ui_color'        => 'required|string|max_length[7]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $ticketDate = $this->request->getPost('ticket_date');

        $this->ticketModel->update($ticketId, [
            'name'            => $this->request->getPost('name'),
            'ticket_date' => empty($ticketDate) ? null : $ticketDate,
            'ticket_category' => $this->request->getPost('ticket_category'),
            'price'           => $this->request->getPost('price'),
            'quantity_total'  => $this->request->getPost('quantity_total'),
            'ui_color'        => $this->request->getPost('ui_color'),
            'description'     => $this->request->getPost('description')
        ]);

        return redirect()->to("/admin/events/$eventId/tickets")->with('message', 'Tiket berhasil diperbarui.');
    }

    public function duplicate($eventId, $ticketId)
    {
        $ticket = $this->ticketModel->find($ticketId);

        if ($ticket) {
            $newTicket = $ticket;
            unset($newTicket['id']); // Hapus ID lama biar jadi record baru
            $newTicket['name'] = $newTicket['name'] . ' (Copy)'; // Kasih tanda
            $newTicket['quantity_sold'] = 0; // Reset penjualan
            
            $this->ticketModel->insert($newTicket);
            return redirect()->back()->with('message', 'Tiket berhasil diduplikasi! Tinggal edit dikit.');
        }

        return redirect()->back()->with('error', 'Tiket tidak ditemukan.');
    }
}