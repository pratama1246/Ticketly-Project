<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    // POST /api/auth/login
    public function login()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Validasi gagal.',
                'data'    => $this->validator->getErrors()
            ]);
        }

        $email    = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        // Cari user di auth_identities langsung
        // Bypass Shield session biar gak bentrok
        $db       = \Config\Database::connect();
        $identity = $db->table('auth_identities')
            ->where('type', 'email_password')
            ->where('secret', $email)
            ->get()
            ->getRowArray();

        if (!$identity) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Email atau password salah.',
                'data'    => null
            ]);
        }

        // Verifikasi password pakai password_verify
        // Shield pakai bcrypt standar, kompatibel
        if (!password_verify($password, $identity['secret2'])) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Email atau password salah.',
                'data'    => null
            ]);
        }

        $userId = $identity['user_id'];

        // Ambil data user dari tabel users
        $userModel = new UserModel();
        $user      = $userModel->find($userId);

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'User tidak ditemukan.',
                'data'    => null
            ]);
        }

        // Generate JWT
        $token = createJWT($userId, $email);

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Login berhasil.',
            'data'    => [
                'token' => $token,
                'user'  => [
                    'id'       => $user['id'],
                    'username' => $user['username'],
                    'email'    => $user['email'],
                    'foto'     => $user['foto']
                        ? base_url('uploads/profile/' . $user['foto'])
                        : null,
                ]
            ]
        ]);
    }

    // POST /api/auth/register
    public function register()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[30]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[auth_identities.secret]',
            'password' => 'required|min_length[8]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Validasi gagal.',
                'data'    => $this->validator->getErrors()
            ]);
        }

        // Pakai ShieldUserModel tapi tanpa attempt/session
        $shieldUsers = new \CodeIgniter\Shield\Models\UserModel();

        $user = new \CodeIgniter\Shield\Entities\User([
            'username' => $this->request->getVar('username'),
            'email'    => $this->request->getVar('email'),
            'password' => $this->request->getVar('password'),
        ]);

        try {
            $shieldUsers->save($user);
            $newUser = $shieldUsers->findByCredentials([
                'email' => $this->request->getPost('email')
            ]);
            $newUser->addGroup('user');
        
        if (!$newUser) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Gagal membuat akun. Silakan coba lagi.',
                'data'    => null
            ]);
        }

        $newUser->addGroup('user');

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Gagal membuat akun: ' . $e->getMessage(),
                'data'    => null
            ]);
        }

        return $this->response->setStatusCode(201)->setJSON([
            'status'  => 'success',
            'message' => 'Registrasi berhasil. Silakan login.',
            'data'    => null
        ]);
    }

    // POST /api/auth/logout
    public function logout()
    {
        // JWT stateless — Flutter tinggal hapus token dari storage
        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Logout berhasil.',
            'data'    => null
        ]);
    }
}