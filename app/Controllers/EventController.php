<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\TicketTypeModel;

class EventController extends BaseController
{
    // Detail Event SLUG
    public function detail($slug = null)
    {
        $eventModel = new EventModel();
        $ticketModel = new TicketTypeModel();
        
        $event = $eventModel->where('slug', $slug)->first();

        if (!$event) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $tickets = $ticketModel->where('event_id', $event['id'])->findAll();
        
        $totalStock = 0;
        $totalSold = 0;

        foreach ($tickets as $t) {
            $totalStock += $t['quantity_total'];
            $totalSold += $t['quantity_sold'];
        }

        $now = new \DateTime();
        $eventDate = new \DateTime($event['event_date']);
        
        $remaining = $totalStock - $totalSold;
        $percentageLeft = ($totalStock > 0) ? ($remaining / $totalStock) * 100 : 0;

        // Status Berlangsung
        $status = [
            'text'        => 'Sedang Berlangsung',
            'color'       => 'bg-green-500 text-white',
            'icon'        => '<svg class="w-3 h-3 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/></svg>',
            'purchasable' => true
        ];

        // Telah Berakhir
        if ($now > $eventDate) {
            $status = [
                'text'        => 'Telah Berakhir',
                'color'       => 'bg-gray-500 text-white', 
                'icon'        => '<svg class="w-3 h-3 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                'purchasable' => false
            ];
        }
        // Terjual Habis
        elseif ($totalStock > 0 && $remaining <= 0) {
            $status = [
                'text'        => 'Terjual Habis',
                'color'       => 'bg-red-600 text-white',
                'icon'        => '<svg class="w-3 h-3 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>',
                'purchasable' => false
            ];
        }
        // Segera Habis
        elseif ($percentageLeft <= 20) {
            $status = [
                'text'        => 'Segera Habis',
                'color'       => 'bg-yellow-400 text-yellow-900',
                'icon'        => '<svg class="w-3 h-3 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/></svg>',
                'purchasable' => true
            ];
        }

        $data = [
            'event'  => $event,
            'status' => $status,
            'title'  => $event['name']
        ];

        return view('layout/header', $data)
             . view('event_detail', $data)
             . view('layout/footer');
    }

    // Pemilihan Tiket SLUG
    public function select($slug = null)
    {
        $eventModel = new EventModel();
        $ticketModel = new TicketTypeModel();

        $event = $eventModel->where('slug', $slug)->first();

        if (!$event) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Event tidak ditemukan untuk: $slug");
        }

        $ticketTypes = $ticketModel->where('event_id', $event['id'])->findAll();

        $data = [
            'title'        => 'Pilih Tiket - ' . $event['name'],
            'event'        => $event,
            'ticket_types' => $ticketTypes
        ];

        echo view('layout/header', $data);
        echo view('select_tickets', $data);
        echo view('layout/footer');
    }
}