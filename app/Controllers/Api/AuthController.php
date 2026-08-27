<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PasswordResetModel;

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

        $db       = \Config\Database::connect();
        $identity = $db->table('auth_identities')
            ->where('type', 'email_password')
            ->where('secret', $email, true)
            ->get()
            ->getRowArray();

        if (!$identity) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Email atau password salah.',
                'data'    => null
            ]);
        }

        if (!password_verify($password, $identity['secret2'])) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Email atau password salah.',
                'data'    => null
            ]);
        }

        $userId = $identity['user_id'];

        $userModel = new UserModel();
        $user      = $userModel->find($userId);

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'User tidak ditemukan.',
                'data'    => null
            ]);
        }

        $token = createJWT($userId, $email);

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Login berhasil.',
            'data'    => [
                'token' => $token,
                'user'  => [
                    'id'       => $user['id'],
                    'username' => $user['username'],
                    'email'    => $email,
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
            'username' => 'required|min_length[3]|max_length[30]|alpha_numeric_space|is_unique[users.username]',
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

        $shieldUsers = new \CodeIgniter\Shield\Models\UserModel();

        $user = new \CodeIgniter\Shield\Entities\User([
            'username' => $this->request->getVar('username'),
            'email'    => $this->request->getVar('email'),
            'password' => $this->request->getVar('password'),
        ]);

        try {
            $shieldUsers->save($user);

            // $newUser = $shieldUsers->findByCredentials([
            // 'email' => $this->request->getPost('email')
            // ]);

            $userId = $shieldUsers->getInsertID();
            $newUser = $shieldUsers->find($userId);

            if (!$newUser) {
                return $this->response->setStatusCode(500)->setJSON([
                    'status'  => 'error',
                    'message' => 'Gagal membuat akun. Silakan coba lagi.',
                    'data'    => null
                ]);
            }

            // Force-activate supaya user langsung bisa login tanpa email verification.
            // Diperlukan karena EmailActivator dinonaktifkan di Auth.php.
            // Tanpa activate(), user inactive dan tidak bisa login via Shield session
            // maupun findByCredentials() pada call berikutnya.
            // Saat production: hapus baris activate() ini
            $newUser->activate();
            $shieldUsers->save($newUser);

            // addGroup satu kali saja setelah activate
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
        $authHeader = $this->request->getHeaderLine('Authorization');
        if (!empty($authHeader) && str_starts_with($authHeader, 'Bearer ')) {
            $token = substr($authHeader, 7);
            helper('jwt');
            blacklistJWT($token);
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Logout berhasil.',
            'data'    => null
        ]);
    }

    // POST /api/auth/forgot-password
    public function forgotPassword()
    {
        $rules = [
            'email' => 'required|valid_email',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Validasi gagal.',
                'data'    => $this->validator->getErrors()
            ]);
        }

        $email = $this->request->getVar('email');

        $db       = \Config\Database::connect();
        $identity = $db->table('auth_identities')
            ->where('type', 'email_password')
            ->where('secret', $email, true)
            ->get()
            ->getRowArray();

        if (!$identity) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Email tidak terdaftar.',
                'data'    => null
            ]);
        }

        // Generate 6-digit OTP code
        $code = (string) random_int(100000, 999999);
        $hashedCode = password_hash($code, PASSWORD_BCRYPT);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $resetModel = new PasswordResetModel();

        // Delete old codes for this email
        $resetModel->where('email', $email)->delete();

        // Save new hashed code
        $resetModel->save([
            'email'      => $email,
            'code'       => $hashedCode,
            'expires_at' => $expiresAt
        ]);

        // Send email via CodeIgniter Email service
        try {
            $emailService = \Config\Services::email();
            $emailService->setFrom('noreply@ticketly.mytamakikii.web.id', 'Ticketly System');
            $emailService->setTo($email);
            $emailService->setSubject('Kode Verifikasi Reset Password Anda');

            $htmlContent = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px;'>
                    <h2 style='color: #1a56db; text-align: center;'>Reset Password Ticketly</h2>
                    <p>Halo,</p>
                    <p>Kami menerima permintaan untuk menyetel ulang kata sandi akun Anda. Gunakan kode verifikasi di bawah ini untuk melanjutkan:</p>
                    <div style='background-color: #f3f4f6; padding: 15px; border-radius: 6px; text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 4px; color: #1f2937; margin: 20px 0;'>
                        {$code}
                    </div>
                    <p style='color: #6b7280; font-size: 14px;'>Kode verifikasi ini berlaku selama 15 menit. Jangan bagikan kode ini kepada siapa pun.</p>
                    <p>Jika Anda tidak meminta ini, silakan abaikan email ini.</p>
                    <hr style='border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;' />
                    <p style='color: #9ca3af; font-size: 12px; text-align: center;'>&copy; " . date('Y') . " Ticketly. All rights reserved.</p>
                </div>
            ";

            $emailService->setMessage($htmlContent);
            $emailService->send();
        } catch (\Exception $e) {
            log_message('error', 'Forgot Password Email Exception: ' . $e->getMessage());
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Kode verifikasi telah dikirim ke email Anda.',
            'data'    => null
        ]);
    }

    // POST /api/auth/verify-code
    public function verifyCode()
    {
        $rules = [
            'email' => 'required|valid_email',
            'code'  => 'required|min_length[6]|max_length[10]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Validasi gagal.',
                'data'    => $this->validator->getErrors()
            ]);
        }

        $email = $this->request->getVar('email');
        $code  = (string) $this->request->getVar('code');

        $resetModel = new PasswordResetModel();
        $records = $resetModel->where('email', $email)
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->findAll();

        $matched = false;
        foreach ($records as $record) {
            if (password_verify($code, $record['code']) || $code === $record['code']) {
                $matched = true;
                break;
            }
        }

        if (!$matched) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Kode verifikasi tidak valid atau telah kedaluwarsa.',
                'data'    => null
            ]);
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Kode verifikasi berhasil diverifikasi.',
            'data'    => null
        ]);
    }

    // POST /api/auth/reset-password
    public function resetPassword()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'code'     => 'required',
            'password' => 'required|min_length[8]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Validasi gagal.',
                'data'    => $this->validator->getErrors()
            ]);
        }

        $email    = $this->request->getVar('email');
        $code     = (string) $this->request->getVar('code');
        $password = $this->request->getVar('password');

        $resetModel = new PasswordResetModel();
        $records = $resetModel->where('email', $email)
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->findAll();

        $matched = false;
        foreach ($records as $record) {
            if (password_verify($code, $record['code']) || $code === $record['code']) {
                $matched = true;
                break;
            }
        }

        if (!$matched) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Kode verifikasi tidak valid atau telah kedaluwarsa.',
                'data'    => null
            ]);
        }

        $db       = \Config\Database::connect();
        $identity = $db->table('auth_identities')
            ->where('type', 'email_password')
            ->where('secret', $email, true)
            ->get()
            ->getRowArray();

        if (!$identity) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'User tidak ditemukan.',
                'data'    => null
            ]);
        }

        $userId = $identity['user_id'];
        $shieldUsers = new \CodeIgniter\Shield\Models\UserModel();
        $user = $shieldUsers->find($userId);

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'User tidak ditemukan.',
                'data'    => null
            ]);
        }

        // Set the password which will hash it automatically
        $user->password = $password;
        if (!$shieldUsers->save($user)) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Gagal mengubah kata sandi.',
                'data'    => null
            ]);
        }

        // Delete the reset code immediately since it has been consumed (single-use)
        $resetModel->where('email', $email)->delete();

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Kata sandi berhasil diubah. Silakan login kembali.',
            'data'    => null
        ]);
    }
}