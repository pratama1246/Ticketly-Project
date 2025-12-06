<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemsModel;
use App\Models\EventModel;
use App\Models\TicketTypeModel; // Tambahan Model

class DashboardController extends BaseController
{
    public function index()
    {
        $orderModel = new OrderModel();
        $orderItemsModel = new OrderItemsModel();
        $eventModel = new EventModel();
        
        // 1. Bersihkan order kadaluarsa otomatis
        $orderModel->autoExpireOrders();

        // =========================================================
        // BAGIAN 1: STATISTIK KARTU (CARD STATS)
        // =========================================================

        // Total Pendapatan (Status Completed)
        $totalRevenue = $orderModel
            ->where('status', 'completed')
            ->selectSum('order_total', 'total')
            ->first()['total'] ?? 0;

        // Total Tiket Terjual
        $totalTickets = $orderItemsModel
            ->join('orders', 'orders.id = order_items.order_id')
            ->where('orders.status', 'completed')
            ->selectSum('order_items.quantity', 'total')
            ->first()['total'] ?? 0;

        // Total Transaksi Berhasil
        $totalOrders = $orderModel
            ->where('status', 'completed')
            ->countAllResults();

        // Total Event Aktif
        $totalEvents = $eventModel->countAllResults();

        // 5 Pesanan Terbaru (List Bawah)
        $recentOrders = $orderModel
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();

        // =========================================================
        // BAGIAN 2: DATA GRAFIK (CHARTS) - INI YANG KURANG TADI
        // =========================================================
        
        // A. Data Grafik Pendapatan (Line Chart - 7 Hari Terakhir)
        $revenueData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            
            // Hitung omzet per tanggal tersebut
            $sum = $orderModel->where('status', 'completed')
                              ->like('created_at', $date)
                              ->selectSum('order_total', 'total')
                              ->first()['total'] ?? 0;
            
            $revenueData[] = [
                'date' => $date,
                'total' => (int)$sum
            ];
        }

        // B. Data Grafik Event Terlaris (Doughnut Chart)
        // Kita hitung berdasarkan total tiket terjual per event
        $topEvents = $eventModel->select('events.name as category, SUM(ticket_types.quantity_sold) as total_sold')
                                ->join('ticket_types', 'ticket_types.event_id = events.id', 'left')
                                ->groupBy('events.id')
                                ->orderBy('total_sold', 'DESC')
                                ->limit(5)
                                ->findAll();

        // C. [BARU] Grafik Metode Pembayaran
        $paymentMethods = $orderModel
            ->where('status', 'completed')
            ->select('payment_method, COUNT(*) as total_usage')
            ->groupBy('payment_method')
            ->orderBy('total_usage', 'DESC')
            ->findAll();

        // D. [BARU] Grafik Status Pesanan
        $orderStats = $orderModel
            ->select('status, COUNT(*) as total')
            ->groupBy('status')
            ->findAll();

        // Handle data kosong biar grafik gak error
        if (empty($topEvents)) {
            $topEvents = []; 
        }

        // =========================================================
        // BAGIAN 3: KIRIM KE VIEW
        // =========================================================
        $data = [
            'title'        => 'Admin Dashboard',
            
            // Data Kartu
            'totalRevenue' => $totalRevenue,
            'totalTickets' => $totalTickets,
            'totalOrders'  => $totalOrders,
            'totalEvents'  => $totalEvents,
            'recentOrders' => $recentOrders,

            // Data Grafik (PENTING!)
            'chart_data'   => [
                'revenue'    => $revenueData,
                'categories' => $topEvents,
                'payments'   => $paymentMethods,
                'statuses'   => $orderStats
            ]
        ];
        
        echo view('admin/layout/header', $data);
        echo view('admin/dashboard', $data);
        echo view('admin/layout/footer');
    }
}