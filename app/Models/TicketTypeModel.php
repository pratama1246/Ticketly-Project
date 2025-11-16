<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketTypeModel extends Model
{
    protected $table            = 'ticket_types';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // Kolom yang boleh diisi
    protected $allowedFields    = [
        'event_id',
        'name',
        'price',
        'quantity_total',
        'quantity_sold'
    ];

    // Kita tidak pakai 'created_at' & 'updated_at' di tabel ini
    protected $useTimestamps = false;
}