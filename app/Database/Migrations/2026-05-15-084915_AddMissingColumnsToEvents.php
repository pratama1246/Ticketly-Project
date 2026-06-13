<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingColumnsToEvents extends Migration
{
    public function up()
    {
        $this->forge->addColumn('events', [
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
                'after'      => 'name'
            ],
            'category' => [
                'type'       => 'ENUM',
                'constraint' => ['concert', 'festival', 'event'],
                'default'    => 'event',
                'null'       => true,
                'after'      => 'description'
            ],
            'is_featured' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => true,
                'after'      => 'category'
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => true,
                'after'      => 'is_featured'
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'published'],
                'default'    => 'published',
                'null'       => true,
                'after'      => 'sort_order'
            ],
            'event_end_date' => [
                'type'  => 'DATETIME',
                'null'  => true,
                'after' => 'event_date'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('events', ['slug', 'category', 'is_featured', 'sort_order', 'status', 'event_end_date']);
    }
}
