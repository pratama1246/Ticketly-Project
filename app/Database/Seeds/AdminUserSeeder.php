<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // 1. Dapatkan Model User
        $users = new UserModel();
        // 2. Buat data user baru
        $user = new User([
            'username'   => 'admin',
            'email'      => 'admin@ticketly.com', // Ganti dengan emailmu
            'password'   => 'AdminKUYangGantengTicketly654!@',      // Ganti dengan password kuat
            'first_name' => 'Admin',
            'last_name'  => 'Ticketly'
        ]);

        // 3. Simpan user ke database
        // (forceActivate() akan otomatis mengaktifkan user)
        $users->save($user);

        // 4. Ambil user yang baru saja dibuat (untuk mendapatkan ID-nya)
        $user = $users->findByCredentials(['email' => 'admin@ticketly.com']);

        // 5. Tambahkan user ke grup 'admin'
        $user->addGroup('admin');

        echo "User admin berhasil dibuat dengan email: admin@ticketly.com";
    }
}