<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;


// Tabel events
class CreateEventsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11, // Di dokumen INT, kita beri panjang 11
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true, // Kita buat boleh null
            ],
            'venue' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true, // Kita buat boleh null
            ],
            'event_date' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'poster_image' => [ // Path ke file gambar
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true, // Boleh null
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            // Kita juga tambahkan seatmap dari contoh statismu
            'seatmap_image' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true, 
            ],
        ]);
        $this->forge->addKey('id', true); // Menjadikan 'id' sebagai Primary Key
        $this->forge->createTable('events'); // Membuat tabel 'events'
    }

    public function down()
    {
        $this->forge->dropTable('events'); // Perintah untuk menghapus tabel
    }
}