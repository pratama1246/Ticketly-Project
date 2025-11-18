<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\TicketTypeModel;

class EventController extends BaseController
{

    // Menampilkan halaman detail event DINAMIS berdasarkan ID
    public function detail($id = null)
    {
        $eventModel = new EventModel();
        $data['event'] = $eventModel->find($id);

        // Cek jika datanya tidak ada
        if (empty($data['event'])) {
            // Tampilkan halaman error 404 bawaan CodeIgniter
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Event tidak ditemukan untuk ID: ' . $id);
        }

        echo view('layout/header');
        echo view('event_detail', $data);
        echo view('layout/footer');
    }


    // Dibuat untuk halaman pemilihan tiket.
    public function select($id = null)
    {
        $eventModel = new EventModel();
        $ticketModel = new TicketTypeModel();

        $data['event'] = $eventModel->find($id);
        
        // Ambil tiket DINAMIS dari database
        $data['ticket_types'] = $ticketModel->where('event_id', $id)->findAll();

        // Cek jika event-nya tidak ada
        if (empty($data['event'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Event tidak ditemukan untuk ID: ' . $id);
        }
        
        // Tampilkan view BARU
        echo view('layout/header');
        echo view('select_tickets', $data); // Tampilkan view 'select_tickets'
        echo view('layout/footer');
    }
}