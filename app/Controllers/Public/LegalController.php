<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;

class LegalController extends BaseController
{
    /**
     * Tampilan Web: Syarat & Ketentuan (T&C)
     */
    public function terms()
    {
        return view('public/terms', [
            'title' => 'Syarat & Ketentuan - Ticketly',
        ]);
    }

    /**
     * Tampilan Web: Kebijakan Privasi (Privacy Policy)
     */
    public function privacy()
    {
        return view('public/privacy', [
            'title' => 'Kebijakan Privasi - Ticketly',
        ]);
    }

    /**
     * Endpoint API: Syarat & Ketentuan (Untuk Mobile Flutter)
     * GET /api/terms
     */
    public function apiTerms()
    {
        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Syarat & Ketentuan berhasil diambil.',
            'data'    => [
                'title'        => 'Syarat & Ketentuan Pembelian Tiket',
                'last_updated' => '2026-08-26',
                'sections'     => [
                    [
                        'heading' => '1. Ketentuan Umum Pembelian Tiket',
                        'content' => 'Tiket yang dibeli melalui platform Ticketly bersifat resmi dan mengikat. Setiap pembeli wajib mengisi data diri yang valid dan sesuai dengan kartu identitas resmi (KTP/Paspor/SIM).'
                    ],
                    [
                        'heading' => '2. Batasan Kuota Pembelian',
                        'content' => 'Untuk menjaga keadilan dan mencegah praktik percaloan (scalping), pembelian tiket dibatasi maksimal 4 (empat) tiket per Nomor Induk Kependudukan (NIK) / akun untuk setiap event.'
                    ],
                    [
                        'heading' => '3. Batas Waktu Pembayaran',
                        'content' => 'Setiap transaksi memiliki batas waktu pembayaran selama 15 (lima belas) menit sejak pesanan dibuat. Jika melewati batas waktu, pesanan akan dibatalkan secara otomatis dan kuota tiket dikembalikan ke sistem publik.'
                    ],
                    [
                        'heading' => '4. Larangan Bot & Kecurangan Sistem',
                        'content' => 'Dilarang keras menggunakan perangkat lunak otomatis, bot script, atau metode manipulatif lainnya untuk membeli tiket. Ticketly berhak membatalkan pesanan sepihak tanpa pengembalian dana jika ditemukan indikasi kecurangan.'
                    ],
                    [
                        'heading' => '5. Kebijakan Pembatalan & Refund',
                        'content' => 'Tiket yang telah berhasil dibayar tidak dapat dikembalikan (non-refundable) atau ditukar dengan uang tunai, kecuali jika event dibatalkan secara resmi oleh pihak penyelenggara (Event Organizer).'
                    ],
                    [
                        'heading' => '6. Pemindaian Tiket di Lokasi Acara (Gate Check-in)',
                        'content' => 'E-Tiket resmi berupa QR Code yang sah wajib ditunjukkan saat memasuki venue acara. Tiket hanya berlaku untuk 1 (satu) kali pemindaian pintu masuk.'
                    ],
                ]
            ]
        ]);
    }

    /**
     * Endpoint API: Kebijakan Privasi (Untuk Mobile Flutter)
     * GET /api/privacy
     */
    public function apiPrivacy()
    {
        return $this->response->setStatusCode(200)->setJSON([
            'status'  => 'success',
            'message' => 'Kebijakan Privasi berhasil diambil.',
            'data'    => [
                'title'        => 'Kebijakan Privasi & Perlindungan Data Pribadi',
                'last_updated' => '2026-08-26',
                'sections'     => [
                    [
                        'heading' => '1. Komitmen Kepatuhan UU PDP',
                        'content' => 'Ticketly berkomitmen penuh melindungi hak privasi pengguna sesuai dengan Undang-Undang No. 27 Tahun 2022 tentang Perlindungan Data Pribadi (UU PDP) Republik Indonesia.'
                    ],
                    [
                        'heading' => '2. Data Pribadi yang Kami Kumpulkan',
                        'content' => 'Kami mengumpulkan data yang Anda berikan secara langsung saat registrasi dan pemesanan tiket, meliputi: Nama Lengkap, Alamat Email, Nomor Telepon/WhatsApp, dan Nomor Induk Kependudukan (NIK).'
                    ],
                    [
                        'heading' => '3. Tujuan Penggunaan Data',
                        'content' => 'Data Anda digunakan untuk: verifikasi keabsahan kepemilikan tiket, penerbitan invoice dan E-Tiket via email/aplikasi, pencegahan penipuan transaksi, serta komunikasi penting terkait perubahan jadwal event.'
                    ],
                    [
                        'heading' => '4. Keamanan & Enkripsi Data',
                        'content' => 'Seluruh transmisi data dilindungi dengan enkripsi SSL/TLS 256-bit standar industri perbankan. Kata sandi akun dienkripsi menggunakan hashing Bcrypt yang tidak dapat didekripsi.'
                    ],
                    [
                        'heading' => '5. Pembagian Data ke Pihak Ketiga',
                        'content' => 'Kami tidak pernah menjual data pribadi Anda. Data Anda hanya diteruskan kepada Payment Gateway resmi (Midtrans) untuk memproses pembayaran dan kepada Event Organizer resmi untuk keperluan validasi gerbang masuk konser.'
                    ],
                    [
                        'heading' => '6. Hak Pemilik Data Pribadi',
                        'content' => 'Anda memiliki hak untuk melihat, memperbarui, meminta penghapusan akun, atau membatasi pemrosesan data pribadi Anda sewaktu-waktu melalui menu Pengaturan Akun atau menghubungi tim bantuan Ticketly.'
                    ],
                ]
            ]
        ]);
    }
}
