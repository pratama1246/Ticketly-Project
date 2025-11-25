<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\OrderModel;
use App\Models\OrderItemsModel;
use App\Models\EventModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $orderModel = new OrderModel();
        $orderItemsModel = new OrderItemsModel();
        $eventModel = new EventModel();
        
        $orderModel->autoExpireOrders();

        // Ambil Total Pendapatan (hanya dari pesanan yang 'completed')
        $totalRevenue = $orderModel
            ->where('status', 'completed')
            ->selectSum('order_total', 'total')
            ->first()['total'] ?? 0;

        // Ambil Total Tiket Terjual (hanya dari pesanan 'completed')
        $totalTickets = $orderItemsModel
            ->join('orders', 'orders.id = order_items.order_id')
            ->where('orders.status', 'completed')
            ->selectSum('order_items.quantity', 'total')
            ->first()['total'] ?? 0;

        // Ambil Total Pesanan
        $totalOrders = $orderModel
            ->where('status', 'completed')
            ->countAllResults();

        // Ambil Total Event (semua event)
        $totalEvents = $eventModel->countAllResults();

        // Ambil 5 Pesanan Terbaru
        $recentOrders = $orderModel
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();

        $data = [
            'title'        => 'Admin Dashboard',
            'totalRevenue' => $totalRevenue,
            'totalTickets' => $totalTickets,
            'totalOrders'  => $totalOrders,
            'totalEvents'  => $totalEvents,
            'recentOrders' => $recentOrders,
        ];
        
        echo view('admin/layout/header', $data);
        echo view('admin/dashboard', $data);
        echo view('admin/layout/footer');
    }
}