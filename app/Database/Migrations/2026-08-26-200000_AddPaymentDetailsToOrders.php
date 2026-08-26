<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentDetailsToOrders extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('orders')) {
            $fields = [
                'payment_va_number' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                    'after'      => 'payment_method',
                ],
                'payment_bill_key' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                    'after'      => 'payment_va_number',
                ],
                'payment_biller_code' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => true,
                    'after'      => 'payment_bill_key',
                ],
                'payment_qr_url' => [
                    'type'  => 'TEXT',
                    'null'  => true,
                    'after' => 'payment_biller_code',
                ],
                'payment_deeplink_url' => [
                    'type'  => 'TEXT',
                    'null'  => true,
                    'after' => 'payment_qr_url',
                ],
                'payment_expired_at' => [
                    'type'  => 'DATETIME',
                    'null'  => true,
                    'after' => 'payment_deeplink_url',
                ],
                'midtrans_transaction_id' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                    'after'      => 'payment_expired_at',
                ],
                'payment_status_raw' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => true,
                    'after'      => 'midtrans_transaction_id',
                ],
            ];

            $this->forge->addColumn('orders', $fields);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('orders')) {
            $this->forge->dropColumn('orders', [
                'payment_va_number',
                'payment_bill_key',
                'payment_biller_code',
                'payment_qr_url',
                'payment_deeplink_url',
                'payment_expired_at',
                'midtrans_transaction_id',
                'payment_status_raw',
            ]);
        }
    }
}
