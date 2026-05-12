<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemsModel extends Model
 {
 protected $table            = 'order_items';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $returnType       = 'array';
  protected $useSoftDeletes   = false;
  protected $protectFields    = true;
  protected $allowedFields    = [
    'order_id', 'ticket_type_id', 'quantity', 'price_per_ticket', 'ticket_code', 'seat_id'
  ];

  protected $useTimestamps = false;
}