<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\OrderModel;
use App\Models\OrderItemsModel;
use App\Models\EventModel;

class ProfileController extends BaseController
{
    protected $userModel;
    protected $orderModel;
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->orderModel = new OrderModel();
        $this->orderItemsModel = new OrderItemsModel();
        $this->eventModel = new EventModel();
    }

    // Halaman Profil Saya
    public function index()
    {
        $data = [
            'Title' => 'Profil Saya',
            'user' => auth()->user()
        ];

        echo view('layout/header', $data);
        echo view('profile/index', $data);
        echo view('layout/footer');
    }

    // Halaman Riwayat Transaksi
    public function transactions()
    {
        $userId = auth()->id();
        $user = $this->userModel->find($userId);

        $orders = $this->orderModel->where('user_id', $userId)
                                   ->orderBy('created_at', 'DESC')
                                   ->findAll();

        $data = [
            'title'  => 'Riwayat Transaksi',
            'user'   => $user,
            'orders' => $orders
        ];

        echo view('layout/header', $data);
        echo view('profile/history', $data);
        echo view('layout/footer');
    }

    // Halaman Detail Transaksi
    public function detail($orderId)
    {
        $userId = auth()->id();
        
        $order = $this->orderModel->where('user_id', $userId)->find($orderId);

        if (!$order) {
            return redirect()->to('/profile/history')->with('error', 'Transaksi tidak ditemukan.');
        }

        $user = $this->userModel->find($userId);

        $items = $this->orderItemsModel->select('order_items.*, ticket_types.name as ticket_name, events.name as event_name, seats.label, seats.seat_row, seats.seat_number')
            ->join('ticket_types', 'ticket_types.id = order_items.ticket_type_id', 'left')
            ->join('events', 'events.id = ticket_types.event_id', 'left')
            ->join('seats', 'seats.id = order_items.seat_id', 'left')
            ->where('order_id', $orderId)
            ->findAll();

        $data = [
            'title' => 'Detail Transaksi #' . $order['trx_id'],
            'user'  => $user,
            'order' => $order,
            'items' => $items
        ];

        echo view('layout/header', $data);
        echo view('profile/detail', $data);
        echo view('layout/footer');
    }

    // Edit Profil
    public function edit()
    {
        $data = [
            'Title' => 'Edit Profil',
            'user' => auth()->user(),
            'validation' => \Config\Services::validation()
        ];

        echo view('layout/header', $data);
        echo view('profile/edit', $data);
        echo view('layout/footer');
    }

    // Update Profil
    public function update()
    {
        $user = auth()->user();
        $id   = $user->id;

        $rules = [
            'username' => "required|min_length[3]|max_length[30]|is_unique[users.username,id,$id]",
            'email'    => "required|valid_email|is_unique[users.email,id,$id]",
            'foto'     => 'permit_empty|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]|max_size[foto,2048]'
        ];

        $fotoBaru = $user->foto;
        $fileFoto = $this->request->getFile('foto');

        if ($fileFoto && $fileFoto->isValid()) {
            $fotoBaru = $fileFoto->getRandomName();
            $fileFoto->move('uploads/profile', $fotoBaru);
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'foto'     => $fotoBaru
        ];

        $this->userModel->update($id, $data);

        return redirect()->to('/profile')->with('success', 'Profil berhasil diperbarui!');
    }
}
