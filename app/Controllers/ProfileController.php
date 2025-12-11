<?php

namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
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
