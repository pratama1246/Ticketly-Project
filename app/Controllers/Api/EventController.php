<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\EventModel;
use App\Models\TicketTypeModel;
use App\Models\OrderModel;

class EventController extends BaseController
{
    protected $eventModel;

    public function __construct()
    {
        $this->eventModel = new EventModel();
    }

    // GET /api/events
    // Query params: ?category=concert&q=keyword&page=1&limit=10
    public function index()
    {
        (new OrderModel())->autoExpireOrders();

        $category = $this->request->getGet('category');
        $keyword  = $this->request->getGet('q');
        $page     = (int) ($this->request->getGet('page') ?? 1);
        $limit    = (int) ($this->request->getGet('limit') ?? 10);

        // Clamp supaya tidak abuse
        $limit  = max(1, min($limit, 50));
        $page   = max(1, $page);
        $offset = ($page - 1) * $limit;

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

        // Hitung total sebelum limit/offset — false agar query tidak direset
        $total  = $builder->countAllResults(false);

        $events = $builder->orderBy('event_date', 'ASC')
                          ->limit($limit, $offset)
                          ->findAll();

        $formatted = array_map(function ($event) {
            return $this->formatEvent($event);
        }, $events);

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Data event berhasil diambil.',
            'data'    => $formatted,   // tetap flat array, konsisten dengan endpoint list lain
            'meta'    => [             // sidecar — hanya muncul di endpoint paginatable
                'total'        => $total,
                'per_page'     => $limit,
                'current_page' => $page,
                'last_page'    => (int) ceil($total / $limit),
            ]
        ]);
    }

    // GET /api/events/:slug
    public function show($slug = null)
    {
        (new OrderModel())->autoExpireOrders();

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

        $ticketModel = new TicketTypeModel();
        $tickets     = $ticketModel->where('event_id', $event['id'])->findAll();

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
            ];
        }, $tickets);

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Detail event berhasil diambil.',
            'data'    => [
                ...$this->formatEvent($event),
                'description'   => $event['description'],
                'seatmap_image' => $event['seatmap_image']
                    ? base_url($event['seatmap_image'])
                    : null,
                'event_status'  => $eventStatus,
                'total_stock'   => $totalStock,
                'total_sold'    => $totalSold,
                'tickets'       => $formattedTickets,
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

    // GET /api/events/landing
    public function landing()
    {
        $featuredEvents = $this->eventModel
            ->where('is_featured', 1)
            ->where('status', 'published')
            ->orderBy("CASE WHEN sort_order = 0 THEN 9999 ELSE sort_order END", "ASC", false)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $concerts = $this->eventModel
            ->where('category', 'concert')
            ->where('status', 'published')
            ->orderBy('event_date', 'ASC')
            ->findAll(4);

        $festivals = $this->eventModel
            ->where('category', 'festival')
            ->where('status', 'published')
            ->orderBy('event_date', 'ASC')
            ->findAll(4);

        $otherEvents = $this->eventModel
            ->where('category', 'event')
            ->where('status', 'published')
            ->orderBy('event_date', 'ASC')
            ->findAll(4);

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Data landing page berhasil diambil.',
            'data'    => [
                'featured'  => array_map(fn($e) => $this->formatEvent($e), $featuredEvents),
                'concerts'  => array_map(fn($e) => $this->formatEvent($e), $concerts),
                'festivals' => array_map(fn($e) => $this->formatEvent($e), $festivals),
                'events'    => array_map(fn($e) => $this->formatEvent($e), $otherEvents),
            ]
        ]);
    }

    // Helper — format event ringkas untuk list
    private function formatEvent(array $event): array
    {
        return [
            'id'             => $event['id'],
            'name'           => $event['name'],
            'slug'           => $event['slug'],
            'category'       => $event['category'],
            'venue'          => $event['venue'],
            'event_date'     => $event['event_date'],
            'event_end_date' => $event['event_end_date'] ?? null,
            'is_featured'    => (bool) $event['is_featured'],
            'poster_image'   => $event['poster_image']
                ? base_url($event['poster_image'])
                : null,
        ];
    }
}