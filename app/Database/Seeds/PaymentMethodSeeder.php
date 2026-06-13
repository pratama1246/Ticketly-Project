<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('payment_methods');

        // Disable FK checks and truncate table
        $db->disableForeignKeyChecks();
        $builder->truncate();
        $db->enableForeignKeyChecks();

        $data = [
            [
                'id'         => 11,
                'name'       => 'BCA Virtual Account',
                'code'       => 'bca',
                'type'       => 'virtual_account',
                'logo_image' => 'assets/payment/bca.svg',
                'is_active'  => 1,
            ],
            [
                'id'         => 12,
                'name'       => 'BNI Virtual Account',
                'code'       => 'bni',
                'type'       => 'virtual_account',
                'logo_image' => 'assets/payment/bni.svg',
                'is_active'  => 1,
            ],
            [
                'id'         => 13,
                'name'       => 'BRI Virtual Account',
                'code'       => 'bri',
                'type'       => 'virtual_account',
                'logo_image' => 'assets/payment/bri.svg',
                'is_active'  => 1,
            ],
            [
                'id'         => 14,
                'name'       => 'Mandiri Bill',
                'code'       => 'mandiri_bill',
                'type'       => 'virtual_account',
                'logo_image' => 'assets/payment/mandiri_bill.svg',
                'is_active'  => 1,
            ],
            [
                'id'         => 15,
                'name'       => 'GoPay',
                'code'       => 'gopay',
                'type'       => 'ewallet',
                'logo_image' => 'assets/payment/gopay.svg',
                'is_active'  => 1,
            ],
            [
                'id'         => 16,
                'name'       => 'OVO',
                'code'       => 'ovo',
                'type'       => 'ewallet',
                'logo_image' => 'assets/payment/ovo.svg',
                'is_active'  => 1,
            ],
            [
                'id'         => 17,
                'name'       => 'DANA',
                'code'       => 'dana',
                'type'       => 'ewallet',
                'logo_image' => 'assets/payment/dana.svg',
                'is_active'  => 1,
            ],
            [
                'id'         => 18,
                'name'       => 'ShopeePay',
                'code'       => 'shopeepay',
                'type'       => 'ewallet',
                'logo_image' => 'assets/payment/shopeepay.svg',
                'is_active'  => 1,
            ],
            [
                'id'         => 19,
                'name'       => 'Allo Bank',
                'code'       => 'allobank',
                'type'       => 'other',
                'logo_image' => 'assets/payment/allobank.svg',
                'is_active'  => 1,
            ],
            [
                'id'         => 20,
                'name'       => 'Akulaku PayLater',
                'code'       => 'akulaku',
                'type'       => 'other',
                'logo_image' => 'assets/payment/akulaku.svg',
                'is_active'  => 1,
            ],
        ];

        $builder->insertBatch($data);
        echo "Payment methods successfully seeded.\n";
    }
}
