<?php

namespace App\Models;

use CodeIgniter\Model;
use PHPUnit\Framework\Attributes\Ticket;

class OrderItemsModel extends Model
 {
 protected $table            = 'order_items';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $returnType       = 'array';
  protected $useSoftDeletes   = false;
  protected $protectFields    = true;
  protected $allowedFields    = [
    'order_id', 'ticket_type_id', 'quantity', 'price_per_ticket', 'ticket_code'
  ];

  // Tidak ada timestamps di tabel ini
  protected $useTimestamps = false;
}