<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
// 1. Panggil semua Model yang kita butuhkan
use App\Models\OrderModel;
use App\Models\OrderItemsModel;
use App\Models\EventModel;

class DashboardController extends BaseController
{
    public function index()
    {
        // 2. Siapkan instance Model
        $orderModel = new OrderModel();
        $orderItemsModel = new OrderItemsModel();
        $eventModel = new EventModel();

        // 3. Ambil data statistik
        
        // Ambil Total Pendapatan (hanya dari pesanan yang 'completed')
        $totalRevenue = $orderModel
            ->where('status', 'completed')
            ->selectSum('order_total', 'total') // 'total' adalah alias
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

        // 4. Kirim semua data ke view
        $data = [
            'title'        => 'Admin Dashboard',
            'totalRevenue' => $totalRevenue,
            'totalTickets' => $totalTickets,
            'totalOrders'  => $totalOrders,
            'totalEvents'  => $totalEvents,
            'recentOrders' => $recentOrders,
        ];
        
        // 5. Muat layout lengkap
        echo view('admin/layout/header', $data);
        echo view('admin/dashboard', $data);
        echo view('admin/layout/footer');
    }
}