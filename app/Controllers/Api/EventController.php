<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\EventModel;
use App\Models\TicketTypeModel;

class EventController extends BaseController
{
    protected $eventModel;

    public function __construct()
    {
        $this->eventModel = new EventModel();
    }

    // GET /api/events
    public function index()
    {
        $category = $this->request->getGet('category');
        $keyword  = $this->request->getGet('q');

        $builder = $this->eventModel->where('status', 'published');

        if (!empty($category)) {
            $builder->where('category', $category);
        }

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('name', $keyword)
                ->orLike('venue', $keyword)
                ->groupEnd();
        }

        $events = $builder->orderBy('event_date', 'ASC')->findAll();

        // Format data biar rapi di Flutter
        $formatted = array_map(function ($event) {
            return $this->formatEvent($event);
        }, $events);

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Data event berhasil diambil.',
            'data'    => $formatted
        ]);
    }

    // GET /api/events/:slug
    public function show($slug = null)
    {
        $event = $this->eventModel
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$event) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Event tidak ditemukan.',
                'data'    => null
            ]);
        }

        // Ambil tiket sekalian
        $ticketModel = new TicketTypeModel();
        $tickets     = $ticketModel->where('event_id', $event['id'])->findAll();

        // Hitung status event
        $totalStock = 0;
        $totalSold  = 0;
        foreach ($tickets as $t) {
            $totalStock += $t['quantity_total'];
            $totalSold  += $t['quantity_sold'];
        }

        $now       = new \DateTime();
        $eventDate = new \DateTime($event['event_date']);
        $remaining = $totalStock - $totalSold;
        $pct       = ($totalStock > 0) ? ($remaining / $totalStock) * 100 : 0;

        if ($now > $eventDate) {
            $eventStatus = 'ended';
        } elseif ($totalStock > 0 && $remaining <= 0) {
            $eventStatus = 'sold_out';
        } elseif ($pct <= 20) {
            $eventStatus = 'almost_sold';
        } else {
            $eventStatus = 'available';
        }

        $formattedTickets = array_map(function ($t) {
            return [
                'id'             => $t['id'],
                'name'           => $t['name'],
                'ticket_date'    => $t['ticket_date'],
                'ticket_category'=> $t['ticket_category'],
                'price'          => (int) $t['price'],
                'ui_color'       => $t['ui_color'],
                'description'    => $t['description'],
                'quantity_total' => (int) $t['quantity_total'],
                'quantity_sold'  => (int) $t['quantity_sold'],
                'quantity_left'  => (int) $t['quantity_total'] - (int) $t['quantity_sold'],
            ];
        }, $tickets);

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Detail event berhasil diambil.',
            'data'    => [
                ...$this->formatEvent($event),
                'description' => $event['description'],
                'seatmap_image' => $event['seatmap_image']
                    ? base_url($event['seatmap_image'])
                    : null,
                'event_status' => $eventStatus,
                'total_stock'  => $totalStock,
                'total_sold'   => $totalSold,
                'tickets'      => $formattedTickets,
            ]
        ]);
    }

    // GET /api/events/featured
    public function featured()
    {
        $events = $this->eventModel
            ->where('is_featured', 1)
            ->where('status', 'published')
            ->orderBy("CASE WHEN sort_order = 0 THEN 9999 ELSE sort_order END", "ASC", false)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $formatted = array_map(fn($e) => $this->formatEvent($e), $events);

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Featured events berhasil diambil.',
            'data'    => $formatted
        ]);
    }

    // Helper — format event ringkas buat list
    private function formatEvent(array $event): array
    {
        return [
            'id'           => $event['id'],
            'name'         => $event['name'],
            'slug'         => $event['slug'],
            'category'     => $event['category'],
            'venue'        => $event['venue'],
            'event_date'   => $event['event_date'],
            'event_end_date' => $event['event_end_date'] ?? null,
            'is_featured'  => (bool) $event['is_featured'],
            'poster_image' => $event['poster_image']
                ? base_url($event['poster_image'])
                : null,
        ];
    }
}