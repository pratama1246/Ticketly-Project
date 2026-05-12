<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSeatsTable extends Migration
{
    public function up()
    {
        // Tabel Master Kursi
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'event_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'ticket_type_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true, // Kursi ini terhubung ke kategori apa (misal: VIP A)
            ],
            'seat_row' => [
                'type'       => 'VARCHAR',
                'constraint' => '10', // Contoh: "A", "B", "VIP"
            ],
            'seat_number' => [
                'type'       => 'INT',
                'constraint' => 5,    // Contoh: 1, 2, 3
            ],
            'label' => [
                'type'       => 'VARCHAR',
                'constraint' => '20', // Gabungan: "A-1", "B-12" (Biar gampang di query)
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('ticket_type_id', 'ticket_types', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('seats');
    }

    public function down()
    {
        $this->forge->dropTable('seats');
    }
}