<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCheckinAndVouchers extends Migration
{
    public function up()
    {
        // 1. Tambah kolom check-in & attendee ke order_items
        if ($this->db->tableExists('order_items')) {
            $orderItemFields = [
                'is_checked_in' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                    'after'      => 'ticket_code',
                ],
                'checked_in_at' => [
                    'type'  => 'DATETIME',
                    'null'  => true,
                    'after' => 'is_checked_in',
                ],
                'checked_in_by' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                    'after'      => 'checked_in_at',
                ],
                'attendee_name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                    'after'      => 'checked_in_by',
                ],
                'attendee_email' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                    'after'      => 'attendee_name',
                ],
                'attendee_identity_number' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => true,
                    'after'      => 'attendee_email',
                ],
            ];

            $this->forge->addColumn('order_items', $orderItemFields);
        }

        // 2. Buat tabel vouchers untuk sistem promo
        if (!$this->db->tableExists('vouchers')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'code' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'unique'     => true,
                ],
                'name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                ],
                'discount_type' => [
                    'type'       => 'ENUM',
                    'constraint' => ['percent', 'fixed'],
                    'default'    => 'fixed',
                ],
                'discount_value' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 0,
                ],
                'min_purchase' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 0,
                ],
                'max_discount' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                ],
                'quota' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 0,
                ],
                'quota_used' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 0,
                ],
                'start_date' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'end_date' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'is_active' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 1,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addKey('is_active');
            $this->forge->createTable('vouchers', true);
        }

        // 3. Tambah kolom voucher & discount ke orders
        if ($this->db->tableExists('orders')) {
            $orderFields = [
                'voucher_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'order_total',
                ],
                'voucher_code' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => true,
                    'after'      => 'voucher_id',
                ],
                'discount_amount' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 0,
                    'after'      => 'voucher_code',
                ],
            ];

            $this->forge->addColumn('orders', $orderFields);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('order_items')) {
            $this->forge->dropColumn('order_items', [
                'is_checked_in',
                'checked_in_at',
                'checked_in_by',
                'attendee_name',
                'attendee_email',
                'attendee_identity_number',
            ]);
        }

        if ($this->db->tableExists('orders')) {
            $this->forge->dropColumn('orders', [
                'voucher_id',
                'voucher_code',
                'discount_amount',
            ]);
        }

        $this->forge->dropTable('vouchers', true);
    }
}
