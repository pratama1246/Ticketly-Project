<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $users = new UserModel();

        // Cek apakah user admin sudah ada
        $existing = $users->findByCredentials(['email' => 'admin@ticketly.com']);
        if ($existing) {
            $existing->password = 'admin123';
            $users->save($existing);
            echo "User admin sudah ada. Kata sandi diperbarui menjadi: admin123\n";
            return;
        }

        $user = new User([
            'username'   => 'admin',
            'email'      => 'admin@ticketly.com',
            'password'   => 'admin123',
            'first_name' => 'Admin',
            'last_name'  => 'Ticketly'
        ]);

        $users->save($user);

        // Ambil kembali untuk add group
        $user = $users->findByCredentials(['email' => 'admin@ticketly.com']);
        $user->addGroup('admin');

        echo "User admin berhasil dibuat dengan email: admin@ticketly.com dan kata sandi: admin123\n";
    }
}