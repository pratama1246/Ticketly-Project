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
    'identity_number', 'birth_date', 'payment_method', 'order_total', 'status',
  ];

  protected $useTimestamps = true;
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';

  public function autoExpireOrders()
    {
        $db = \Config\Database::connect();
        
        $timeLimit = date('Y-m-d H:i:s', strtotime('-15 minutes'));

        $expiredOrders = $this->where('status', 'Pending')
                              ->where('created_at <', $timeLimit)
                              ->findAll();

        if (empty($expiredOrders)) {
            return 0; 
        }

        $ticketModel = new \App\Models\TicketTypeModel();
        $orderItemModel = new \App\Models\OrderItemsModel();

        foreach ($expiredOrders as $order) {
            $items = $orderItemModel->where('order_id', $order['id'])->findAll();

            foreach ($items as $item) {
                $ticketModel->where('id', $item['ticket_type_id'])
                            ->set('quantity_sold', 'quantity_sold - ' . $item['quantity'], false)
                            ->update();
            }

            $this->update($order['id'], ['status' => 'Expired']);
        }

        return count($expiredOrders);
    }
}