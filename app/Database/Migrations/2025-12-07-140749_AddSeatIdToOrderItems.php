<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSeatIdToOrderItems extends Migration
{
   public function up()
{
    $this->forge->addColumn('order_items', [
        'seat_id' => [
            'type'       => 'INT',
            'constraint' => 11,
            'unsigned'   => true,
            'null'       => true, // Boleh null (karena ada tiket Festival/Standing yang ga pake kursi)
            'after'      => 'ticket_type_id'
        ]
    ]);
    // Tambah FK manual di database client atau pake raw sql kalau perlu
}
public function down()
{
    $this->forge->dropColumn('order_items', 'seat_id');
}

}

   