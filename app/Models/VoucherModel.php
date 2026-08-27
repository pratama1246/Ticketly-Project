<?php

namespace App\Models;

use CodeIgniter\Model;

class VoucherModel extends Model
{
    protected $table            = 'vouchers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'code',
        'name',
        'discount_type',
        'discount_value',
        'min_purchase',
        'max_discount',
        'quota',
        'quota_used',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Validasi dan hitung diskon voucher untuk nilai transaksi tertentu.
     */
    public function validateAndCalculate(string $code, int $subtotal): array
    {
        $voucher = $this->where('code', strtoupper(trim($code)))
            ->where('is_active', 1)
            ->first();

        if (!$voucher) {
            return [
                'valid'   => false,
                'message' => 'Kode voucher tidak valid atau sudah tidak aktif.',
                'data'    => null,
            ];
        }

        $now = date('Y-m-d H:i:s');
        if (!empty($voucher['start_date']) && $now < $voucher['start_date']) {
            return [
                'valid'   => false,
                'message' => 'Voucher belum dapat digunakan.',
                'data'    => null,
            ];
        }

        if (!empty($voucher['end_date']) && $now > $voucher['end_date']) {
            return [
                'valid'   => false,
                'message' => 'Voucher sudah kedaluwarsa.',
                'data'    => null,
            ];
        }

        if ($voucher['quota'] > 0 && $voucher['quota_used'] >= $voucher['quota']) {
            return [
                'valid'   => false,
                'message' => 'Kuota penggunaan voucher telah habis.',
                'data'    => null,
            ];
        }

        if ($subtotal < (int) $voucher['min_purchase']) {
            return [
                'valid'   => false,
                'message' => 'Minimum pembelian untuk voucher ini adalah Rp ' . number_format($voucher['min_purchase'], 0, ',', '.'),
                'data'    => null,
            ];
        }

        // Hitung nilai diskon
        $discountAmount = 0;
        if ($voucher['discount_type'] === 'percent') {
            $discountAmount = (int) round(($subtotal * (int) $voucher['discount_value']) / 100);
            if (!empty($voucher['max_discount']) && $discountAmount > (int) $voucher['max_discount']) {
                $discountAmount = (int) $voucher['max_discount'];
            }
        } else {
            $discountAmount = (int) $voucher['discount_value'];
        }

        // Diskon tidak boleh melebihi subtotal
        if ($discountAmount > $subtotal) {
            $discountAmount = $subtotal;
        }

        return [
            'valid'           => true,
            'message'         => 'Voucher berhasil diterapkan!',
            'voucher_id'      => (int) $voucher['id'],
            'voucher_code'    => $voucher['code'],
            'voucher_name'    => $voucher['name'],
            'discount_type'   => $voucher['discount_type'],
            'discount_value'  => (int) $voucher['discount_value'],
            'discount_amount' => $discountAmount,
            'final_total'     => max(0, $subtotal - $discountAmount),
        ];
    }
}
