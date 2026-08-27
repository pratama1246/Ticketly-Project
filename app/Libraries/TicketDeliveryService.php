<?php

namespace App\Libraries;

use App\Models\OrderModel;
use App\Models\OrderItemsModel;
use App\Models\EventModel;
use Dompdf\Dompdf;
use CodeIgniter\Email\Email;
use SimpleSoftwareIO\QrCode\Generator as QrCodeGenerator;

class TicketDeliveryService
{
    private static string $queueFile = WRITEPATH . 'queue/ticket_jobs.json';

    /**
     * Masukkan job pengiriman tiket ke antrean background queue.
     */
    public static function pushToQueue(int $orderId): bool
    {
        $queueDir = WRITEPATH . 'queue';
        if (!is_dir($queueDir)) {
            @mkdir($queueDir, 0777, true);
        }

        $jobs = self::getQueuedJobs();
        if (!in_array($orderId, $jobs, true)) {
            $jobs[] = $orderId;
            file_put_contents(self::$queueFile, json_encode(array_values($jobs), JSON_PRETTY_PRINT));
        }

        return true;
    }

    /**
     * Ambil seluruh ID order yang ada dalam antrean.
     */
    public static function getQueuedJobs(): array
    {
        if (!file_exists(self::$queueFile)) {
            return [];
        }

        $content = file_get_contents(self::$queueFile);
        $data = json_decode($content, true);

        return is_array($data) ? $data : [];
    }

    /**
     * Ambil 1 job dari antrean (FIFO).
     */
    public static function popJob(): ?int
    {
        $jobs = self::getQueuedJobs();
        if (empty($jobs)) {
            return null;
        }

        $orderId = array_shift($jobs);
        file_put_contents(self::$queueFile, json_encode(array_values($jobs), JSON_PRETTY_PRINT));

        return $orderId;
    }

    /**
     * Eksekusi langsung pembuatan PDF dan pengiriman email untuk suatu pesanan.
     */
    public static function process(int $orderId): bool
    {
        $orderModel      = new OrderModel();
        $orderItemsModel = new OrderItemsModel();
        $eventModel      = new EventModel();

        $order = $orderModel->find($orderId);
        if (!$order || empty($order['email'])) {
            log_message('error', "[TicketDeliveryService] Order ID {$orderId} tidak ditemukan atau email kosong.");
            return false;
        }

        $items = $orderItemsModel
            ->select('order_items.*, ticket_types.name as ticket_name, ticket_types.event_id, seats.label, seats.seat_row, seats.seat_number')
            ->join('ticket_types', 'ticket_types.id = order_items.ticket_type_id')
            ->join('seats', 'seats.id = order_items.seat_id', 'left')
            ->where('order_items.order_id', $orderId)
            ->findAll();

        if (empty($items)) {
            log_message('error', "[TicketDeliveryService] Item order untuk Order ID {$orderId} tidak ditemukan.");
            return false;
        }

        $eventId = $items[0]['event_id'] ?? null;
        $event   = $eventId ? $eventModel->find($eventId) : null;

        $ticketList = [];
        $qrCode     = new QrCodeGenerator();

        foreach ($items as $item) {
            $seatLabel = 'Free Seating';
            if (!empty($item['label'])) {
                $seatLabel = $item['label'];
            } elseif (!empty($item['seat_row'])) {
                $seatLabel = $item['seat_row'] . '-' . $item['seat_number'];
            }

            $qrContent = !empty($item['ticket_code']) ? $item['ticket_code'] : 'ERR-' . $item['id'];
            $qrSvg     = $qrCode->format('svg')->size(250)->generate($qrContent);
            $qrBase64  = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

            $ticketList[] = [
                'type' => $item['ticket_name'],
                'seat' => $seatLabel,
                'code' => $item['ticket_code'],
                'qr'   => $qrBase64,
            ];
        }

        try {
            $htmlContent = view('ticket_template', [
                'event'   => $event,
                'order'   => $order,
                'tickets' => $ticketList,
            ]);

            $dompdf = new Dompdf();
            $dompdf->loadHtml($htmlContent);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $pdfOutput = $dompdf->output();

            $email = \Config\Services::email();
            $email->setFrom('noreply@ticketly.mytamakikii.web.id', 'Ticketly System');
            $email->setTo($order['email']);
            $email->setSubject('E-Tiket Resmi: ' . ($event['name'] ?? 'Ticketly'));
            $email->setMessage("$htmlContent<br><br>Terima kasih telah memesan tiket di Ticketly.");
            $email->attach($pdfOutput, 'attachment', 'E-Ticket-' . $order['trx_id'] . '.pdf', 'application/pdf');

            if ($email->send()) {
                log_message('info', "[TicketDeliveryService] E-Tiket berhasil dikirimkan ke {$order['email']} (TRX: {$order['trx_id']}).");
                return true;
            }

            log_message('error', "[TicketDeliveryService] Gagal mengirim email ke {$order['email']}: " . $email->printDebugger(['headers']));
            return false;

        } catch (\Exception $e) {
            log_message('error', "[TicketDeliveryService] Exception saat memproses tiket {$orderId}: " . $e->getMessage());
            return false;
        }
    }
}
