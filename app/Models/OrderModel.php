<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
 {
 protected $table            = 'orders';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $returnType       = 'array';
  protected $useSoftDeletes   = false;
  protected $protectFields    = true;
  protected $allowedFields    = [
    'user_id', 'trx_id', 'first_name', 'last_name', 'email', 'phone_number',
    'identity_number', 'birth_date', 'payment_method', 'order_total', 'status'
  ];

  // Menggunakan created_at dan updated_at secara otomatis
  protected $useTimestamps = true;
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
}