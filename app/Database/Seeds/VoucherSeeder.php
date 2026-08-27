<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class VoucherSeeder extends Seeder
{
    public function run()
    {
        $vouchers = [
            [
                'code'           => 'TIKETLYHEMAT20',
                'name'           => 'Diskon Heboh 20% Khusus Event Pilihan',
                'discount_type'  => 'percent',
                'discount_value' => 20,
                'min_purchase'   => 100000,
                'max_discount'   => 50000,
                'quota'          => 100,
                'quota_used'     => 0,
                'start_date'     => date('Y-m-d H:i:s'),
                'end_date'       => date('Y-m-d H:i:s', strtotime('+3 months')),
                'is_active'      => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'code'           => 'WELCOME50K',
                'name'           => 'Potongan Langsung Rp 50.000 Pengguna Baru',
                'discount_type'  => 'fixed',
                'discount_value' => 50000,
                'min_purchase'   => 200000,
                'max_discount'   => null,
                'quota'          => 50,
                'quota_used'     => 0,
                'start_date'     => date('Y-m-d H:i:s'),
                'end_date'       => date('Y-m-d H:i:s', strtotime('+1 year')),
                'is_active'      => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'code'           => 'EARLYBIRD10',
                'name'           => 'Diskon Early Bird 10%',
                'discount_type'  => 'percent',
                'discount_value' => 10,
                'min_purchase'   => 50000,
                'max_discount'   => 25000,
                'quota'          => 200,
                'quota_used'     => 0,
                'start_date'     => date('Y-m-d H:i:s'),
                'end_date'       => date('Y-m-d H:i:s', strtotime('+6 months')),
                'is_active'      => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
        ];

        $db = \Config\Database::connect();
        foreach ($vouchers as $v) {
            $existing = $db->table('vouchers')->where('code', $v['code'])->get()->getRow();
            if (!$existing) {
                $db->table('vouchers')->insert($v);
            }
        }
    }
}
