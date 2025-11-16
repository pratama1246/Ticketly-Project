<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        // INI ADALAH FUNGSI UNTUK TABEL 'order_items'
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'ticket_type_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, // Ini perbaikan kita
            ],
            'quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'price_per_ticket' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('ticket_type_id', 'ticket_types', 'id', 'SET NULL', 'SET NULL');
        
        $this->forge->createTable('order_items'); // INI TEMPAT YANG BENAR
    }

    public function down()
    {
        $this->forge->dropTable('order_items');
    }
}