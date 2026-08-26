<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPerformanceIndexesAndAlterResetCode extends Migration
{
    public function up()
    {
        // 1. Perbesar kolom code pada password_resets menjadi VARCHAR(255) agar muat hash Bcrypt
        if ($this->db->tableExists('password_resets')) {
            $this->forge->modifyColumn('password_resets', [
                'code' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => false,
                ],
            ]);
        }

        // 2. Tambah Index pada tabel orders
        if ($this->db->tableExists('orders')) {
            $this->db->query("ALTER TABLE `orders` ADD INDEX `idx_orders_user_id` (`user_id`);");
            $this->db->query("ALTER TABLE `orders` ADD INDEX `idx_orders_status_created_at` (`status`, `created_at`);");
            $this->db->query("ALTER TABLE `orders` ADD UNIQUE INDEX `uq_orders_trx_id` (`trx_id`);");
        }

        // 3. Tambah Index pada tabel order_items
        if ($this->db->tableExists('order_items')) {
            $this->db->query("ALTER TABLE `order_items` ADD INDEX `idx_order_items_order_id` (`order_id`);");
            $this->db->query("ALTER TABLE `order_items` ADD INDEX `idx_order_items_ticket_seat` (`ticket_type_id`, `seat_id`);");
        }

        // 4. Tambah Index pada tabel ticket_types
        if ($this->db->tableExists('ticket_types')) {
            $this->db->query("ALTER TABLE `ticket_types` ADD INDEX `idx_ticket_types_event_id` (`event_id`);");
        }

        // 5. Tambah Index & Unique Slug pada tabel events
        if ($this->db->tableExists('events')) {
            $this->db->query("ALTER TABLE `events` ADD UNIQUE INDEX `uq_events_slug` (`slug`);");
            $this->db->query("ALTER TABLE `events` ADD INDEX `idx_events_status_category_date` (`status`, `category`, `event_date`);");
        }

        // 6. Tambah Composite Index pada tabel seats
        if ($this->db->tableExists('seats')) {
            $this->db->query("ALTER TABLE `seats` ADD INDEX `idx_seats_event_ticket` (`event_id`, `ticket_type_id`);");
        }
    }

    public function down()
    {
        // 1. Revert kolom code pada password_resets
        if ($this->db->tableExists('password_resets')) {
            $this->forge->modifyColumn('password_resets', [
                'code' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '10',
                    'null'       => false,
                ],
            ]);
        }

        // 2. Drop Index pada orders
        if ($this->db->tableExists('orders')) {
            $this->db->query("ALTER TABLE `orders` DROP INDEX `idx_orders_user_id`;");
            $this->db->query("ALTER TABLE `orders` DROP INDEX `idx_orders_status_created_at`;");
            $this->db->query("ALTER TABLE `orders` DROP INDEX `uq_orders_trx_id`;");
        }

        // 3. Drop Index pada order_items
        if ($this->db->tableExists('order_items')) {
            $this->db->query("ALTER TABLE `order_items` DROP INDEX `idx_order_items_order_id`;");
            $this->db->query("ALTER TABLE `order_items` DROP INDEX `idx_order_items_ticket_seat`;");
        }

        // 4. Drop Index pada ticket_types
        if ($this->db->tableExists('ticket_types')) {
            $this->db->query("ALTER TABLE `ticket_types` DROP INDEX `idx_ticket_types_event_id`;");
        }

        // 5. Drop Index pada events
        if ($this->db->tableExists('events')) {
            $this->db->query("ALTER TABLE `events` DROP INDEX `uq_events_slug`;");
            $this->db->query("ALTER TABLE `events` DROP INDEX `idx_events_status_category_date`;");
        }

        // 6. Drop Index pada seats
        if ($this->db->tableExists('seats')) {
            $this->db->query("ALTER TABLE `seats` DROP INDEX `idx_seats_event_ticket`;");
        }
    }
}
