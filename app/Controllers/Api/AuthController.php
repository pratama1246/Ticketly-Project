<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

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

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Pakai Shield untuk autentikasi
        $authenticator = auth()->getAuthenticator();

        $result = $authenticator->attempt([
            'email'    => $email,
            'password' => $password
        ]);

        if (!$result->isOK()) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Email atau password salah.',
                'data'    => null
            ]);
        }

        $shieldUser = auth()->user();

        // Ambil data tambahan dari tabel users kita sendiri
        $userModel   = new UserModel();
        $userProfile = $userModel->find($shieldUser->id);

        // Generate JWT token
        $token = createJWT($shieldUser->id, $shieldUser->email);

        // Logout dari session Shield (karena kita pakai JWT, bukan session)
        auth()->logout();

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Login berhasil.',
            'data'    => [
                'token' => $token,
                'user'  => [
                    'id'       => $shieldUser->id,
                    'username' => $userProfile['username'] ?? $shieldUser->username,
                    'email'    => $shieldUser->email,
                    'foto'     => $userProfile['foto'] ?? null,
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

        $shieldUsers = new ShieldUserModel();

        $user = new \CodeIgniter\Shield\Entities\User([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ]);

        try {
            $shieldUsers->save($user);
            $newUser = $shieldUsers->findByCredentials([
                'email' => $this->request->getPost('email')
            ]);
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

    // POST /api/auth/logout  (protected)
    public function logout()
    {
        // JWT stateless, jadi logout cukup dari sisi Flutter
        // (hapus token dari local storage)
        // Endpoint ini tetap disediakan untuk good practice
        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Logout berhasil.',
            'data'    => null
        ]);
    }
}