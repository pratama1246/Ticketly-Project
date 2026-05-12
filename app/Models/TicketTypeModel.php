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

    protected $allowedFields    = [
        'event_id',
        'name',
        'ticket_date',
        'ticket_category',
        'price',
        'quantity_total',
        'quantity_sold',
        'description',
        'ui_color'

    ];

    protected $useTimestamps = false;
}