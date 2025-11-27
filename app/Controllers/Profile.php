<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $user = auth()->user(); 
        return view('profile/index', [
            'user' => $user
        ]);
    }

    public function edit()
    {
        $user = auth()->user();
        return view('profile/edit', [
            'user' => $user
        ]);
    }

    public function update()
    {
        $user = auth()->user();
        $id   = $user->id;

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
