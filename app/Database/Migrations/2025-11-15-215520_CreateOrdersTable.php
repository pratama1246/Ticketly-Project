<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        // INI ADALAH FUNGSI UNTUK TABEL 'orders'
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'first_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'last_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'phone_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'identity_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'birth_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'order_total' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'pending',
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
        $this->forge->createTable('orders'); 
        // HANYA INI, JANGAN ADA createTable('order_items') DI SINI
    }

    public function down()
    {
        $this->forge->dropTable('orders');
    }
}