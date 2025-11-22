<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentMethodModel extends Model
{
    protected $table            = 'payment_methods';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['name', 'code', 'type', 'logo_image', 'is_active'];
    
    // Helper untuk ambil yang aktif saja
    public function getActiveMethods()
    {
        return $this->where('is_active', 1)->findAll();
    }
}