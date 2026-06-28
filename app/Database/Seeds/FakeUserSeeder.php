<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class FakeUserSeeder extends Seeder
{
    public function run()
    {
        $usersModel = new UserModel();

        $fakeUsers = [
            [
                'username' => 'budi_santoso',
                'email'    => 'budi@example.com',
                'password' => 'password123',
            ],
            [
                'username' => 'ani_wijaya',
                'email'    => 'ani@example.com',
                'password' => 'password123',
            ],
            [
                'username' => 'dewi_sari',
                'email'    => 'dewi@example.com',
                'password' => 'password123',
            ],
            [
                'username' => 'rudi_hermawan',
                'email'    => 'rudi@example.com',
                'password' => 'password123',
            ],
        ];

        foreach ($fakeUsers as $userData) {
            // Check if user already exists to avoid duplication
            $existing = $usersModel->findByCredentials(['email' => $userData['email']]);
            if ($existing) {
                echo "User {$userData['email']} already exists. Skipping.\n";
                continue;
            }

            $user = new User([
                'username' => $userData['username'],
                'email'    => $userData['email'],
                'password' => $userData['password'],
            ]);

            try {
                $usersModel->save($user);

                $userId = $usersModel->getInsertID();
                $newUser = $usersModel->find($userId);

                if ($newUser) {
                    $newUser->activate();
                    $usersModel->save($newUser);
                    $newUser->addGroup('user');
                    echo "Fake user created: {$userData['email']} (username: {$userData['username']})\n";
                }
            } catch (\Exception $e) {
                echo "Failed to create {$userData['email']}: " . $e->getMessage() . "\n";
            }
        }
    }
}
