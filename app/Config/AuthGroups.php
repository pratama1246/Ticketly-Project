<?php

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------
     * Mendifinisikan grup (peran) dalam sistem.
     */
    public array $groups = [
        'superadmin' => [
            'title'       => 'Super Admin',
            'description' => 'Akses penuh ke sistem.',
        ],
        'admin' => [ // <-- TAMBAHKAN GRUP INI
            'title'       => 'Admin',
            'description' => 'Bisa mengelola event dan pesanan.',
        ],
        'user' => [
            'title'       => 'User',
            'description' => 'Pengguna reguler / pembeli tiket.',
        ],
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------
     * Mendefinisikan izin (apa yang bisa dilakukan).
     */
    public array $permissions = [
        'admin.access'    => 'Bisa mengakses area admin', // <-- TAMBAHKAN IZIN INI
        'users.create'    => 'Bisa membuat user baru',
        'users.edit'      => 'Bisa mengedit user',
        'users.delete'    => 'Bisa menghapus user',
    ];

    /**
     * --------------------------------------------------------------------
     * Groups to Permissions Matrix
     * --------------------------------------------------------------------
     * Menetapkan izin ke grup.
     */
    public array $matrix = [
        'superadmin' => [
            'admin.*', // Superadmin bisa melakukan semua hal admin
            'users.*',
        ],
        'admin' => [ // <-- TAMBAHKAN MATRIX INI
            'admin.access',
            'users.create',
        ],
        'user' => [],
    ];
}