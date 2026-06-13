<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

use App\Models\UserModel;
use App\Models\OrderModel;
use App\Models\OrderItemsModel;
use App\Models\EventModel;

class ProfileController extends BaseController
{
    protected $userModel;
    protected $orderModel;
    protected $orderItemsModel;
    protected $eventModel;

    public function __construct()
    {
        $this->userModel       = new UserModel();
        $this->orderModel      = new OrderModel();
        $this->orderItemsModel = new OrderItemsModel();
        $this->eventModel      = new EventModel();
    }

    // Halaman Profil Saya
    public function index()
    {
        $data = [
            'title' => 'Profil Saya',
            'user'  => auth()->user()
        ];

        return view('user/profile/index', $data);
    }

    // Halaman Riwayat Transaksi
    public function transactions()
    {
        $userId = auth()->id();
        $user   = $this->userModel->find($userId);

        $orders = $this->orderModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data = [
            'title'  => 'Riwayat Transaksi',
            'user'   => $user,
            'orders' => $orders
        ];

        return view('user/profile/history', $data);
    }

    // Halaman Detail Transaksi
    public function detail($orderId)
    {
        $userId = auth()->id();

        $order = $this->orderModel
            ->where('user_id', $userId)
            ->where('id', (int) $orderId)
            ->first();

        if (!$order) {
            return redirect()->to('/profile/history')->with('error', 'Transaksi tidak ditemukan.');
        }

        $user = $this->userModel->find($userId);

        $items = $this->orderItemsModel
            ->select('order_items.*, ticket_types.name as ticket_name, events.name as event_name, seats.label, seats.seat_row, seats.seat_number')
            ->join('ticket_types', 'ticket_types.id = order_items.ticket_type_id', 'left')
            ->join('events', 'events.id = ticket_types.event_id', 'left')
            ->join('seats', 'seats.id = order_items.seat_id', 'left')
            ->where('order_id', (int) $orderId)
            ->findAll();

        $data = [
            'title' => 'Detail Transaksi #' . esc($order['trx_id']),
            'user'  => $user,
            'order' => $order,
            'items' => $items
        ];

        return view('user/profile/detail', $data);
    }

    // Edit Profil
    public function edit()
    {
        $data = [
            'title'      => 'Edit Profil',
            'user'       => auth()->user(),
            'validation' => \Config\Services::validation()
        ];

        return view('user/profile/edit', $data);
    }

    // Update Profil 
    public function update()
    {
        $user = auth()->user();
        $id   = $user->id;


        if (!$this->validateData(array_merge($this->request->getPost(), ['id' => $id]), 'updateProfile')) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $fotoBaru = $user->foto;
        $fileFoto = $this->request->getFile('foto');

        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            // Hapus foto lama jika ada agar tidak menumpuk
            if (!empty($user->foto)) {
                $oldPath = FCPATH . 'uploads/profile/' . $user->foto;
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $fotoBaru = $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'uploads/profile', $fotoBaru);
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
