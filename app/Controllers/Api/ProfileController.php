<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;

class ProfileController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // GET /api/profile
    public function index()
    {
        $userId = $this->request->userId; // dari JwtFilter

        $user = $this->userModel->find($userId);

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'User tidak ditemukan.',
                'data'    => null
            ]);
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Data profil berhasil diambil.',
            'data'    => [
                'id'       => $user['id'],
                'username' => $user['username'],
                'email'    => $user['email'],
                'foto'     => $user['foto']
                    ? base_url('uploads/profile/' . $user['foto'])
                    : null,
            ]
        ]);
    }

    // POST /api/profile/update
    public function update()
    {
        $userId = $this->request->userId;

        $rules = [
            'username' => "required|min_length[3]|max_length[30]|is_unique[users.username,id,$userId]",
            'email'    => "required|valid_email|is_unique[users.email,id,$userId]",
            'foto'     => 'permit_empty|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]|max_size[foto,2048]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Validasi gagal.',
                'data'    => $this->validator->getErrors()
            ]);
        }

        $existingUser = $this->userModel->find($userId);
        $fotoName     = $existingUser['foto'];

        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            // Hapus foto lama kalau ada
            if (!empty($existingUser['foto'])) {
                $oldPath = FCPATH . 'uploads/profile/' . $existingUser['foto'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $fotoName = $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'uploads/profile', $fotoName);
        }

        $this->userModel->update($userId, [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'foto'     => $fotoName,
        ]);

        $updated = $this->userModel->find($userId);

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Profil berhasil diperbarui.',
            'data'    => [
                'id'       => $updated['id'],
                'username' => $updated['username'],
                'email'    => $updated['email'],
                'foto'     => $updated['foto']
                    ? base_url('uploads/profile/' . $updated['foto'])
                    : null,
            ]
        ]);
    }
}