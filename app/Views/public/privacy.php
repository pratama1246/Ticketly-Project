<?= $this->extend('layout/app') ?>
<?= $this->section('content') ?>

<main class="w-full pt-28 pb-20 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Breadcrumb & Title -->
        <div class="mb-8">
            <nav class="flex text-xs font-semibold text-slate-500 mb-3" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="<?= base_url() ?>" class="hover:text-blue-primary-normal transition-colors">Beranda</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-slate-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            <span class="text-slate-800">Kebijakan Privasi</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kebijakan Privasi & Perlindungan Data</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">Kepatuhan UU No. 27 Tahun 2022 (UU PDP) | Terakhir diperbarui: 26 Agustus 2026</p>
        </div>

        <!-- Content Card -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-10 shadow-flat-sm space-y-8 text-slate-700 leading-relaxed text-sm sm:text-base">
            
            <div class="border-b border-slate-150 pb-6">
                <p class="font-medium text-slate-600">
                    Di <strong>Ticketly</strong>, privasi dan keamanan data pribadi Anda adalah prioritas mutlak kami. Dokumen Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan, dan melindungi informasi pribadi Anda sesuai dengan peraturan perundang-undangan Republik Indonesia.
                </p>
            </div>

            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-green-50 text-green-600 font-extrabold flex items-center justify-center text-sm border border-green-200">1</span>
                    Data Pribadi yang Kami Kumpulkan
                </h2>
                <p>Saat Anda menggunakan layanan Ticketly, kami dapat mengumpulkan jenis informasi berikut:</p>
                <ul class="list-disc list-inside space-y-1.5 text-slate-600 pl-2">
                    <li><strong>Informasi Akun:</strong> Nama lengkap, alamat email, nomor telepon/WhatsApp, dan kata sandi (terenkripsi).</li>
                    <li><strong>Informasi Transaksi:</strong> Nomor Induk Kependudukan (NIK/Paspor) untuk verifikasi tiket, metode pembayaran yang dipilih, dan riwayat pesanan.</li>
                    <li><strong>Informasi Teknis:</strong> Alamat IP, jenis perangkat, dan data log aktivitas untuk keperluan keamanan dan pencegahan penipuan.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-green-50 text-green-600 font-extrabold flex items-center justify-center text-sm border border-green-200">2</span>
                    Tujuan Penggunaan Data
                </h2>
                <p>Data pribadi Anda hanya digunakan untuk tujuan yang sah, antara lain:</p>
                <ul class="list-disc list-inside space-y-1.5 text-slate-600 pl-2">
                    <li>Memproses pemesanan tiket dan menerbitkan E-Tiket resmi dengan kode QR unik.</li>
                    <li>Mengirimkan konfirmasi pembayaran, tiket PDF, dan notifikasi penting terkait jadwal event.</li>
                    <li>Mencegah aktivitas mencurigakan, serangan bot, dan transaksi penipuan.</li>
                    <li>Menyediakan layanan bantuan pelanggan (*Customer Support*) yang responsif.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-green-50 text-green-600 font-extrabold flex items-center justify-center text-sm border border-green-200">3</span>
                    Keamanan & Perlindungan Data
                </h2>
                <p>
                    Kami menerapkan standar keamanan teknis tingkat tinggi, termasuk enkripsi <strong>SSL/TLS 256-bit</strong> pada seluruh transmisi data, hashing kata sandi dengan algoritma <strong>Bcrypt</strong>, serta pembatasan akses database internal yang ketat.
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-green-50 text-green-600 font-extrabold flex items-center justify-center text-sm border border-green-200">4</span>
                    Pembagian Informasi ke Pihak Ketiga
                </h2>
                <p>
                    <strong>Ticketly tidak pernah menjual atau menyewakan data pribadi Anda kepada pihak mana pun.</strong> Data hanya dibagikan kepada mitra terpercaya yang esensial untuk operasional:
                </p>
                <ul class="list-disc list-inside space-y-1.5 text-slate-600 pl-2">
                    <li><strong>Payment Gateway Berlisensi (Midtrans):</strong> Untuk memproses pembayaran secara aman dan terenkripsi.</li>
                    <li><strong>Promotor / Event Organizer:</strong> Khusus daftar nama dan kategori tiket untuk keperluan validasi gate saat hari acara.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-green-50 text-green-600 font-extrabold flex items-center justify-center text-sm border border-green-200">5</span>
                    Hak Anda Sebagai Pemilik Data
                </h2>
                <p>
                    Sesuai ketentuan UU PDP, Anda memiliki hak penuh untuk mengakses, memperbarui data profil Anda, atau mengajukan permohonan penutupan dan penghapusan data akun kapan saja melalui halaman pengaturan atau menghubungi tim kami di <strong>support@ticketly.web.id</strong>.
                </p>
            </section>

        </div>

    </div>
</main>

<?= $this->endSection() ?>
