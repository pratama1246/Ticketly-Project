<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemsModel;
use App\Models\EventModel;
use App\Models\TicketTypeModel;

class OrderController extends BaseController
{
    protected $orderModel;
    protected $orderItemsModel;

    public function __construct()
    {
        $this->orderModel      = new OrderModel();
        $this->orderItemsModel = new OrderItemsModel();
    }

    // GET /api/orders
    public function index()
    {
        $userId  = $_SERVER['JWT_USER_ID'] ?? null;
        
        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized. Silakan login terlebih dahulu.',
                'data'    => null
            ]);
        }

        $orders = $this->orderModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $formatted = array_map(function ($order) {
            return $this->formatOrder($order);
        }, $orders);

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Riwayat order berhasil diambil.',
            'data'    => $formatted
        ]);
    }

    // GET /api/orders/:id
    public function detail($orderId = null)
    {
        $userId  = $_SERVER['JWT_USER_ID'] ?? null;
        
        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized. Silakan login terlebih dahulu.',
                'data'    => null
            ]);
        }

        $order = $this->orderModel
            ->where('user_id', $userId)
            ->where('id', (int) $orderId)
            ->first();

        if (!$order) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Order tidak ditemukan.',
                'data'    => null
            ]);
        }

        $items = $this->orderItemsModel
            ->select('order_items.*, ticket_types.name as ticket_name,
                      ticket_types.ticket_category, events.name as event_name,
                      events.slug as event_slug, events.event_date,
                      events.poster_image,
                      seats.label as seat_label')
            ->join('ticket_types', 'ticket_types.id = order_items.ticket_type_id', 'left')
            ->join('events', 'events.id = ticket_types.event_id', 'left')
            ->join('seats', 'seats.id = order_items.seat_id', 'left')
            ->where('order_id', (int) $orderId)
            ->findAll();

        $formattedItems = array_map(function ($item) {
            return [
                'id'              => $item['id'],
                'ticket_code'     => $item['ticket_code'],
                'ticket_name'     => $item['ticket_name'],
                'ticket_category' => $item['ticket_category'],
                'event_name'      => $item['event_name'],
                'event_date'      => $item['event_date'],
                'seat_label'      => $item['seat_label'] ?? 'Free Seating',
                'price_per_ticket'=> (int) $item['price_per_ticket'],
                'event_poster'    => $item['poster_image']
                    ? base_url($item['poster_image'])
                    : null,
            ];
        }, $items);

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Detail order berhasil diambil.',
            'data'    => [
                ...$this->formatOrder($order),
                'items' => $formattedItems
            ]
        ]);
    }

    // Helper format order ringkas
    private function formatOrder(array $order): array
    {
        return [
            'id'             => $order['id'],
            'trx_id'         => $order['trx_id'],
            'order_total'    => (int) $order['order_total'],
            'status'         => $order['status'],
            'payment_method' => $order['payment_method'],
            'created_at'     => $order['created_at'],
        ];
    }
}