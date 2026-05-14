<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\TicketTypeModel;
use App\Models\EventModel;

class TicketController extends BaseController
{
    // GET /api/events/:eventId/tickets
    public function index($eventId = null)
    {
        $eventModel = new EventModel();
        $event      = $eventModel->find($eventId);

        if (!$event || $event['status'] !== 'published') {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Event tidak ditemukan.',
                'data'    => null
            ]);
        }

        $ticketModel = new TicketTypeModel();
        $tickets     = $ticketModel->where('event_id', $eventId)->findAll();

        $formatted = array_map(function ($t) {
            return [
                'id'              => $t['id'],
                'name'            => $t['name'],
                'ticket_date'     => $t['ticket_date'],
                'ticket_category' => $t['ticket_category'],
                'price'           => (int) $t['price'],
                'ui_color'        => $t['ui_color'],
                'description'     => $t['description'],
                'quantity_total'  => (int) $t['quantity_total'],
                'quantity_sold'   => (int) $t['quantity_sold'],
                'quantity_left'   => (int) $t['quantity_total'] - (int) $t['quantity_sold'],
                'is_available'    => ((int) $t['quantity_total'] - (int) $t['quantity_sold']) > 0,
            ];
        }, $tickets);

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Data tiket berhasil diambil.',
            'data'    => [
                'event_id'   => (int) $eventId,
                'event_name' => $event['name'],
                'tickets'    => $formatted
            ]
        ]);
    }
}