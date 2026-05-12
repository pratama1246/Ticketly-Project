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

        $this->ticketModel->save([
            'event_id'        => $eventId,
            'name'            => $this->request->getPost('name'),
            'ticket_date'     => empty($ticketDate) ? null : $ticketDate,
            'ticket_category' => $this->request->getPost('ticket_category'),
            'price'           => $this->request->getPost('price'),
            'quantity_total'  => $this->request->getPost('quantity_total'),
            'quantity_sold'   => 0,
            'ui_color'        => $this->request->getPost('ui_color'),
            'description'     => $this->request->getPost('description')
        ]);

    $newTicketId = $this->ticketModel->getInsertID();

    $startRow = $this->request->getPost('seat_row_start');
    $endRow   = $this->request->getPost('seat_row_end');
    $perRow   = $this->request->getPost('seats_per_row');

    if (!empty($startRow) && !empty($endRow) && !empty($perRow)) {
        
        $seatData = [];
        $startCode = ord(strtoupper($startRow));
        $endCode   = ord(strtoupper($endRow));

        for ($rowCode = $startCode; $rowCode <= $endCode; $rowCode++) {
            $rowChar = chr($rowCode);
            
            for ($num = 1; $num <= $perRow; $num++) {
                $seatData[] = [
                    'event_id'       => $eventId,
                    'ticket_type_id' => $newTicketId,
                    'seat_row'       => $rowChar,
                    'seat_number'    => $num,
                    'label'          => $rowChar . '-' . $num,
                ];
            }
        }

        if (!empty($seatData)) {
            $db = \Config\Database::connect();
            $db->table('seats')->insertBatch($seatData);
            
            $this->ticketModel->update($newTicketId, ['ticket_category' => 'Seating']);
        }
    }

        return redirect()->to("/admin/events/$eventId/tickets")->with('message', 'Tiket berhasil ditambahkan.');
    }

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

        $db = \Config\Database::connect();
        $builder = $db->table('seats');
        
        $hasSeats = $builder->where('ticket_type_id', $ticketId)->countAllResults() > 0;

        if ($hasSeats) {
            $minRow = $db->table('seats')->where('ticket_type_id', $ticketId)->selectMin('seat_row')->get()->getRow()->seat_row;
            
            $maxRow = $db->table('seats')->where('ticket_type_id', $ticketId)->selectMax('seat_row')->get()->getRow()->seat_row;
            
            $maxNum = $db->table('seats')->where('ticket_type_id', $ticketId)->selectMax('seat_number')->get()->getRow()->seat_number;

            $ticket['seat_row_start'] = $minRow;
            $ticket['seat_row_end']   = $maxRow;
            $ticket['seats_per_row']  = $maxNum;
        } else {
            $ticket['seat_row_start'] = '';
            $ticket['seat_row_end']   = '';
            $ticket['seats_per_row']  = '';
        }

        $data = [
            'title' => 'Edit Tiket: ' . $ticket['name'],
            'event' => $event,
            'ticket' => $ticket, 
            'validation' => \Config\Services::validation()
        ];

        echo view('admin/layout/header', $data);
        echo view('admin/tickets/edit', $data); 
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
            'ticket_date'     => empty($ticketDate) ? null : $ticketDate,
            'ticket_category' => $this->request->getPost('ticket_category'),
            'price'           => $this->request->getPost('price'),
            'quantity_total'  => $this->request->getPost('quantity_total'),
            'ui_color'        => $this->request->getPost('ui_color'),
            'description'     => $this->request->getPost('description')
        ]);

        $startRow = $this->request->getPost('seat_row_start');
        $endRow   = $this->request->getPost('seat_row_end');
        $perRow   = $this->request->getPost('seats_per_row');

        if (!empty($startRow) && !empty($endRow) && !empty($perRow)) {
            
            $db = \Config\Database::connect();
            $db->table('seats')->where('ticket_type_id', $ticketId)->delete();
            

            $seatData = [];
            $startCode = ord(strtoupper($startRow));
            $endCode   = ord(strtoupper($endRow));

            for ($rowCode = $startCode; $rowCode <= $endCode; $rowCode++) {
                $rowChar = chr($rowCode);
                
                for ($num = 1; $num <= $perRow; $num++) {
                    $seatData[] = [
                        'event_id'       => $eventId,
                        'ticket_type_id' => $ticketId,
                        'seat_row'       => $rowChar,
                        'seat_number'    => $num,
                        'label'          => $rowChar . '-' . $num,
                    ];
                }
            }

            if (!empty($seatData)) {
                $db->table('seats')->insertBatch($seatData);
                $msg = 'Data tiket diperbarui & ' . count($seatData) . ' kursi baru ditambahkan.';
            } else {
                $msg = 'Data tiket diperbarui. Tidak ada kursi baru yang ditambahkan (sudah ada semua).';
            }
        } else {
            $msg = 'Data tiket berhasil diperbarui.';
        }

        return redirect()->to("/admin/events/$eventId/tickets")->with('message', $msg);
    }

    // Copy Tiket
    public function duplicate($eventId, $ticketId)
    {
        $ticket = $this->ticketModel->find($ticketId);

        if ($ticket) {
            $newTicket = $ticket;
            unset($newTicket['id']); 
            $newTicket['name'] = $newTicket['name'] . ' (Copy)'; 
            $newTicket['quantity_sold'] = 0;
            
            $this->ticketModel->insert($newTicket);
            $newTicketId = $this->ticketModel->getInsertID();

            $db = \Config\Database::connect();

            $oldSeats = $db->table('seats')->where('ticket_type_id', $ticketId)->get()->getResultArray();

            if (!empty($oldSeats)) {
                $newSeats = [];
                foreach ($oldSeats as $seat) {

                    $seat['ticket_type_id'] = $newTicketId; 
                    unset($seat['id']);
                    
                    $newSeats[] = $seat;
                }
                
                $db->table('seats')->insertBatch($newSeats);
            }

            return redirect()->back()->with('message', 'Tiket berhasil diduplikasi beserta susunan kursinya!');
        }

        return redirect()->back()->with('error', 'Tiket tidak ditemukan.');
    }
}