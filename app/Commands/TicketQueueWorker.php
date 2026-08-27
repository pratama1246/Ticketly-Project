<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\TicketDeliveryService;

class TicketQueueWorker extends BaseCommand
{
    /**
     * Group category command.
     */
    protected $group = 'Ticketly';

    /**
     * Nama command yang dipanggil via CLI.
     */
    protected $name = 'queue:work';

    /**
     * Deskripsi command.
     */
    protected $description = 'Menjalankan background worker untuk memproses antrean pembuatan E-Tiket PDF dan pengiriman email.';

    /**
     * Petunjuk penggunaan.
     */
    protected $usage = 'queue:work [options]';

    /**
     * Opsi command.
     */
    protected $options = [
        '--once' => 'Hanya proses job yang saat ini ada dalam antrean lalu keluar (one-shot batch).',
    ];

    public function run(array $params)
    {
        $runOnce = array_key_exists('once', $params) || CLI::getOption('once');

        CLI::write('[' . date('Y-m-d H:i:s') . '] Ticketly Queue Worker dimulai...', 'green');

        do {
            $orderId = TicketDeliveryService::popJob();

            if ($orderId !== null) {
                CLI::write('[' . date('Y-m-d H:i:s') . "] Memproses pengiriman E-Tiket untuk Order ID: {$orderId}...", 'yellow');

                $success = TicketDeliveryService::process($orderId);

                if ($success) {
                    CLI::write("[SUCCESS] Order ID {$orderId} berhasil diproses dan dikirim.", 'green');
                } else {
                    CLI::write("[FAILED] Gagal memproses Order ID {$orderId}. Cek logs.", 'red');
                }
            } else {
                if ($runOnce) {
                    CLI::write('[' . date('Y-m-d H:i:s') . '] Tidak ada antrean job tersisa. Selesai.', 'white');
                    break;
                }
                // Sleep 2 detik sebelum polling antrean berikutnya
                sleep(2);
            }
        } while (!$runOnce);
    }
}
