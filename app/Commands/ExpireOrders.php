<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\OrderModel;

class ExpireOrders extends BaseCommand
{
    /**
     * Group category command.
     */
    protected $group = 'Ticketly';

    /**
     * Nama command yang dipanggil via CLI.
     */
    protected $name = 'orders:expire';

    /**
     * Deskripsi command.
     */
    protected $description = 'Mengecek dan mengkadaluarsakan pesanan pending yang melewati batas waktu 15 menit serta mengembalikan kuota tiket.';

    /**
     * Petunjuk penggunaan.
     */
    protected $usage = 'orders:expire';

    public function run(array $params)
    {
        CLI::write('[' . date('Y-m-d H:i:s') . '] Memeriksa pesanan pending yang kedaluwarsa...', 'yellow');

        $orderModel = new OrderModel();
        $expiredCount = $orderModel->autoExpireOrders();

        if ($expiredCount > 0) {
            CLI::write("[SUCCESS] Sebanyak {$expiredCount} pesanan berhasil dikadaluarsakan dan kuota tiket telah dikembalikan.", 'green');
        } else {
            CLI::write('[INFO] Tidak ada pesanan pending yang melewati batas waktu.', 'white');
        }

        return $expiredCount;
    }
}
