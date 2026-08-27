<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemsModel;
use App\Models\EventModel;
use App\Models\TicketTypeModel;

class ScannerController extends BaseController
{
    protected $orderModel;
    protected $orderItemsModel;
    protected $eventModel;

    public function __construct()
    {
        $this->orderModel      = new OrderModel();
        $this->orderItemsModel = new OrderItemsModel();
        $this->eventModel      = new EventModel();
    }

    /**
     * POST /api/scanner/validate
     * Memvalidasi dan melihat detail tiket tanpa mengubah status check-in (Preview).
     */
    public function validateTicket()
    {
        $ticketCode = trim((string) $this->request->getVar('ticket_code'));

        if (empty($ticketCode)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Kode tiket wajib diisi.',
                'data'    => null,
            ]);
        }

        $ticket = $this->getTicketDetails($ticketCode);

        if (!$ticket) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Tiket tidak ditemukan dalam sistem.',
                'data'    => null,
            ]);
        }

        // Cek status pesanan
        if (strtolower($ticket['order_status']) !== 'completed') {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Tiket tidak valid. Status pesanan: ' . strtoupper($ticket['order_status']),
                'data'    => $ticket,
            ]);
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => $ticket['is_checked_in'] ? 'Tiket valid namun sudah pernah di-check-in.' : 'Tiket valid dan siap check-in.',
            'data'    => $ticket,
        ]);
    }

    /**
     * POST /api/scanner/checkin
     * Eksekusi Check-in dengan Anti-Double Scan Protection (Atomic Update).
     */
    public function checkIn()
    {
        $ticketCode = trim((string) $this->request->getVar('ticket_code'));
        $gateName   = trim((string) ($this->request->getVar('gate') ?? 'Gate Utama'));
        $userId     = $_SERVER['JWT_USER_ID'] ?? null;
        $scannerBy  = $userId ? "Petugas ID: {$userId} ({$gateName})" : $gateName;

        if (empty($ticketCode)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Kode tiket wajib diisi.',
                'data'    => null,
            ]);
        }

        $ticket = $this->getTicketDetails($ticketCode);

        if (!$ticket) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Tiket tidak ditemukan dalam sistem.',
                'data'    => null,
            ]);
        }

        if (strtolower($ticket['order_status']) !== 'completed') {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Gagal check-in. Status pesanan belum lunas (' . strtoupper($ticket['order_status']) . ').',
                'data'    => $ticket,
            ]);
        }

        // 🛡️ ANTI-DOUBLE SCAN PROTECTION (Atomic conditional update)
        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        $query = $db->query(
            "UPDATE order_items 
             SET is_checked_in = 1, checked_in_at = ?, checked_in_by = ? 
             WHERE ticket_code = ? AND is_checked_in = 0",
            [$now, $scannerBy, $ticketCode]
        );

        if ($db->affectedRows() === 0) {
            // Sudah pernah di-check-in sebelumnya! (Fraud / Duplicate Scan attempt)
            $existing = $this->orderItemsModel->where('ticket_code', $ticketCode)->first();
            $checkedAt = $existing['checked_in_at'] ?? 'sebelumnya';
            $checkedBy = $existing['checked_in_by'] ?? 'Petugas';

            return $this->response->setStatusCode(409)->setJSON([
                'status'  => 'error',
                'message' => "PERINGATAN: Tiket sudah pernah digunakan pada {$checkedAt} oleh {$checkedBy}!",
                'data'    => [
                    'ticket_code'   => $ticketCode,
                    'is_checked_in' => true,
                    'checked_in_at' => $checkedAt,
                    'checked_in_by' => $checkedBy,
                    'attendee_name' => $ticket['attendee_name'],
                    'event_name'    => $ticket['event_name'],
                ],
            ]);
        }

        // Ambil data terbaru setelah check-in sukses
        $updatedTicket = $this->getTicketDetails($ticketCode);

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Check-in berhasil! Selamat menikmati acara.',
            'data'    => $updatedTicket,
        ]);
    }

    /**
     * GET /api/scanner/stats/:eventId
     * Statistik real-time kehadiran penonton di gate.
     */
    public function stats($eventId = null)
    {
        if (empty($eventId)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Event ID wajib diisi.',
                'data'    => null,
            ]);
        }

        $event = $this->eventModel->find((int) $eventId);
        if (!$event) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Event tidak ditemukan.',
                'data'    => null,
            ]);
        }

        $db = \Config\Database::connect();
        $stats = $db->query(
            "SELECT 
                COUNT(oi.id) as total_tickets,
                SUM(CASE WHEN oi.is_checked_in = 1 THEN 1 ELSE 0 END) as checked_in_count,
                SUM(CASE WHEN oi.is_checked_in = 0 THEN 1 ELSE 0 END) as remaining_count
             FROM order_items oi
             JOIN orders o ON o.id = oi.order_id
             JOIN ticket_types tt ON tt.id = oi.ticket_type_id
             WHERE tt.event_id = ? AND o.status = 'completed'",
            [(int) $eventId]
        )->getRowArray();

        $totalTickets    = (int) ($stats['total_tickets'] ?? 0);
        $checkedInCount  = (int) ($stats['checked_in_count'] ?? 0);
        $remainingCount  = (int) ($stats['remaining_count'] ?? 0);
        $percentage      = $totalTickets > 0 ? round(($checkedInCount / $totalTickets) * 100, 1) : 0;

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Statistik gate check-in berhasil diambil.',
            'data'    => [
                'event_id'         => (int) $eventId,
                'event_name'       => $event['name'],
                'total_tickets'    => $totalTickets,
                'checked_in_count' => $checkedInCount,
                'remaining_count'  => $remainingCount,
                'percentage'       => $percentage,
            ],
        ]);
    }

    /**
     * Helper query detail tiket lengkap.
     */
    private function getTicketDetails(string $ticketCode): ?array
    {
        helper('masking');

        $db = \Config\Database::connect();
        $row = $db->query(
            "SELECT 
                oi.id as order_item_id,
                oi.ticket_code,
                oi.is_checked_in,
                oi.checked_in_at,
                oi.checked_in_by,
                oi.attendee_name,
                oi.attendee_email,
                oi.attendee_identity_number,
                o.id as order_id,
                o.trx_id,
                o.status as order_status,
                o.first_name as buyer_first_name,
                o.last_name as buyer_last_name,
                o.email as buyer_email,
                o.phone_number as buyer_phone,
                tt.name as ticket_name,
                tt.ticket_category,
                e.id as event_id,
                e.name as event_name,
                e.event_date,
                e.venue as event_location,
                s.label as seat_label,
                s.seat_row,
                s.seat_number
             FROM order_items oi
             JOIN orders o ON o.id = oi.order_id
             JOIN ticket_types tt ON tt.id = oi.ticket_type_id
             JOIN events e ON e.id = tt.event_id
             LEFT JOIN seats s ON s.id = oi.seat_id
             WHERE oi.ticket_code = ?",
            [$ticketCode]
        )->getRowArray();

        if (!$row) {
            return null;
        }

        $seatDisplay = 'Free Seating';
        if (!empty($row['seat_label'])) {
            $seatDisplay = $row['seat_label'];
        } elseif (!empty($row['seat_row'])) {
            $seatDisplay = $row['seat_row'] . '-' . $row['seat_number'];
        }

        $attendeeName = !empty($row['attendee_name']) 
            ? $row['attendee_name'] 
            : trim($row['buyer_first_name'] . ' ' . $row['buyer_last_name']);

        return [
            'ticket_code'              => $row['ticket_code'],
            'is_checked_in'            => (bool) $row['is_checked_in'],
            'checked_in_at'            => $row['checked_in_at'],
            'checked_in_by'            => $row['checked_in_by'],
            'attendee_name'            => $attendeeName,
            'attendee_email_masked'    => !empty($row['attendee_email']) ? mask_email($row['attendee_email']) : mask_email($row['buyer_email']),
            'attendee_identity_masked' => !empty($row['attendee_identity_number']) ? mask_nik($row['attendee_identity_number']) : null,
            'ticket_name'              => $row['ticket_name'],
            'ticket_category'          => $row['ticket_category'],
            'seat'                     => $seatDisplay,
            'event_id'                 => (int) $row['event_id'],
            'event_name'               => $row['event_name'],
            'event_date'               => $row['event_date'],
            'event_location'           => $row['event_location'],
            'order_trx_id'             => $row['trx_id'],
            'order_status'             => $row['order_status'],
        ];
    }
}
