<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\VoucherModel;

class VoucherController extends BaseController
{
    protected $voucherModel;

    public function __construct()
    {
        $this->voucherModel = new VoucherModel();
    }

    /**
     * POST /api/vouchers/apply
     * Cek dan validasi voucher promo berdasarkan subtotal transaksi.
     */
    public function apply()
    {
        $code     = trim((string) $this->request->getVar('code'));
        $subtotal = (int) $this->request->getVar('subtotal');

        if (empty($code)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Kode voucher wajib diisi.',
                'data'    => null,
            ]);
        }

        if ($subtotal <= 0) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => 'Nilai subtotal belanja harus lebih dari 0.',
                'data'    => null,
            ]);
        }

        $result = $this->voucherModel->validateAndCalculate($code, $subtotal);

        if (!$result['valid']) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => $result['message'],
                'data'    => null,
            ]);
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => $result['message'],
            'data'    => [
                'voucher_id'      => $result['voucher_id'],
                'voucher_code'    => $result['voucher_code'],
                'voucher_name'    => $result['voucher_name'],
                'discount_type'   => $result['discount_type'],
                'discount_value'  => $result['discount_value'],
                'discount_amount' => $result['discount_amount'],
                'final_total'     => $result['final_total'],
            ],
        ]);
    }

    /**
     * GET /api/vouchers/active
     * Daftar voucher promo publik yang sedang aktif.
     */
    public function active()
    {
        $now = date('Y-m-d H:i:s');

        $vouchers = $this->voucherModel
            ->where('is_active', 1)
            ->groupStart()
                ->where('start_date IS NULL')
                ->orWhere('start_date <=', $now)
            ->groupEnd()
            ->groupStart()
                ->where('end_date IS NULL')
                ->orWhere('end_date >=', $now)
            ->groupEnd()
            ->findAll();

        $formatted = array_map(function ($v) {
            return [
                'id'             => (int) $v['id'],
                'code'           => $v['code'],
                'name'           => $v['name'],
                'discount_type'  => $v['discount_type'],
                'discount_value' => (int) $v['discount_value'],
                'min_purchase'   => (int) $v['min_purchase'],
                'max_discount'   => $v['max_discount'] ? (int) $v['max_discount'] : null,
                'end_date'       => $v['end_date'],
            ];
        }, $vouchers);

        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Daftar voucher promo aktif berhasil diambil.',
            'data'    => $formatted,
        ]);
    }
}
