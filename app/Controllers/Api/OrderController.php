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

        $status  = $this->request->getGet('status');
        $include = $this->request->getGet('include');

        $builder = $this->orderModel->where('user_id', $userId);
        if (!empty($status)) {
            $builder->where('status', strtolower($status));
        }
        $orders = $builder->orderBy('created_at', 'DESC')->findAll();

        $itemsByOrder = [];
        if ($include === 'items' && !empty($orders)) {
            $orderIds = array_column($orders, 'id');
            $allItems = $this->orderItemsModel
                ->select('order_items.*, ticket_types.name as ticket_name,
                          ticket_types.ticket_category, events.name as event_name,
                          events.slug as event_slug, events.event_date,
                          events.poster_image, events.venue as venue,
                          seats.label as seat_label')
                ->join('ticket_types', 'ticket_types.id = order_items.ticket_type_id', 'left')
                ->join('events', 'events.id = ticket_types.event_id', 'left')
                ->join('seats', 'seats.id = order_items.seat_id', 'left')
                ->whereIn('order_id', $orderIds)
                ->findAll();

            foreach ($allItems as $item) {
                $orderId = (int) $item['order_id'];
                $itemsByOrder[$orderId][] = [
                    'id'                       => $item['id'],
                    'ticket_code'              => $item['ticket_code'],
                    'ticket_name'              => $item['ticket_name'],
                    'ticket_category'          => $item['ticket_category'],
                    'event_name'               => $item['event_name'],
                    'event_date'               => $item['event_date'],
                    'venue'                    => $item['venue'] ?? '',
                    'seat_label'               => $item['seat_label'] ?? 'Free Seating',
                    'price_per_ticket'         => (int) $item['price_per_ticket'],
                    'attendee_name'            => $item['attendee_name'] ?? null,
                    'attendee_email'           => $item['attendee_email'] ?? null,
                    'attendee_identity_number' => $item['attendee_identity_number'] ?? null,
                    'event_poster'             => $item['poster_image']
                        ? base_url($item['poster_image'])
                        : null,
                ];
            }
        }

        $formatted = array_map(function ($order) use ($include, $itemsByOrder) {
            $base = $this->formatOrder($order);
            if ($include === 'items') {
                $base['items'] = $itemsByOrder[(int) $order['id']] ?? [];
            }
            return $base;
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
                      events.poster_image, events.venue as venue,
                      seats.label as seat_label')
            ->join('ticket_types', 'ticket_types.id = order_items.ticket_type_id', 'left')
            ->join('events', 'events.id = ticket_types.event_id', 'left')
            ->join('seats', 'seats.id = order_items.seat_id', 'left')
            ->where('order_id', (int) $orderId)
            ->findAll();

        $formattedItems = array_map(function ($item) {
            return [
                'id'                       => $item['id'],
                'ticket_code'              => $item['ticket_code'],
                'ticket_name'              => $item['ticket_name'],
                'ticket_category'          => $item['ticket_category'],
                'event_name'               => $item['event_name'],
                'event_date'               => $item['event_date'],
                'venue'                    => $item['venue'] ?? '',
                'seat_label'               => $item['seat_label'] ?? 'Free Seating',
                'price_per_ticket'         => (int) $item['price_per_ticket'],
                'attendee_name'            => $item['attendee_name'] ?? null,
                'attendee_email'           => $item['attendee_email'] ?? null,
                'attendee_identity_number' => $item['attendee_identity_number'] ?? null,
                'event_poster'             => $item['poster_image']
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

    // Helper format order ringkas dengan PII Masking UU PDP
    private function formatOrder(array $order): array
    {
        helper('masking');

        return [
            'id'                 => (int) $order['id'],
            'trx_id'             => $order['trx_id'],
            'first_name'         => $order['first_name'] ?? null,
            'email_masked'       => !empty($order['email']) ? mask_email($order['email']) : null,
            'phone_masked'       => !empty($order['phone_number']) ? mask_phone($order['phone_number']) : null,
            'identity_masked'    => !empty($order['identity_number']) ? mask_nik($order['identity_number']) : null,
            'order_total'        => (int) $order['order_total'],
            'status'             => $order['status'],
            'payment_method'     => $order['payment_method'],
            'payment_va_number'  => $order['payment_va_number'] ?? null,
            'payment_qr_url'     => $order['payment_qr_url'] ?? null,
            'payment_expired_at' => $order['payment_expired_at'] ?? null,
            'created_at'         => $order['created_at'],
        ];
    }
}