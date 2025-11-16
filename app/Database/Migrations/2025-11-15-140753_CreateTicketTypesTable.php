<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTicketTypesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'event_id' => [ // Ini adalah 'kunci tamu'
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
            ],
            'quantity_total' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'quantity_sold' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
        ]);
        $this->forge->addKey('id', true); // Primary Key

        // Menambahkan Foreign Key
        // Ini akan menyambungkan ticket_types.event_id ke events.id
        // 'CASCADE' berarti jika event dihapus, tiketnya juga ikut terhapus
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('ticket_types');
    }

    public function down()
    {
        $this->forge->dropTable('ticket_types');
    }
}