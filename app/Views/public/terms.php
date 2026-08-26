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
                            <span class="text-slate-800">Syarat & Ketentuan</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Syarat & Ketentuan Pembelian Tiket</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">Terakhir diperbarui: 26 Agustus 2026</p>
        </div>

        <!-- Content Card -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-10 shadow-flat-sm space-y-8 text-slate-700 leading-relaxed text-sm sm:text-base">
            
            <div class="border-b border-slate-150 pb-6">
                <p class="font-medium text-slate-600">
                    Selamat datang di <strong>Ticketly</strong>. Dengan mengakses, mendaftar, atau melakukan pemesanan tiket pada platform kami (situs web maupun aplikasi mobile), Anda secara hukum terikat dan menyetujui seluruh syarat dan ketentuan berikut ini.
                </p>
            </div>

            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 font-extrabold flex items-center justify-center text-sm border border-blue-200">1</span>
                    Ketentuan Umum Pemesanan
                </h2>
                <p>
                    Setiap pemesan wajib berusia minimal 17 tahun atau memiliki kartu identitas resmi yang diakui pemerintah (KTP, SIM, atau Paspor). Data yang diisikan saat proses pemesanan harus akurat, lengkap, dan dapat dipertanggungjawabkan keasliannya.
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 font-extrabold flex items-center justify-center text-sm border border-blue-200">2</span>
                    Batasan Kuota & Kebijakan Anti-Calo
                </h2>
                <p>
                    Untuk memastikan keadilan bagi seluruh penonton dan memberantas praktik percaloan (scalping), sistem Ticketly membatasi pembelian maksimal <strong>4 (empat) tiket per Nomor Induk Kependudukan (NIK) / akun</strong> untuk setiap event yang sama.
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 font-extrabold flex items-center justify-center text-sm border border-blue-200">3</span>
                    Batas Waktu Pembayaran (15 Menit)
                </h2>
                <p>
                    Setelah pesanan berhasil dibuat, sistem akan menahan kuota tiket selama <strong>15 (lima belas) menit</strong>. Pemesan wajib menyelesaikan pembayaran melalui metode resmi yang dipilih (Virtual Account / E-Wallet) sebelum batas waktu berakhir. Jika waktu habis, pesanan otomatis hangus dan kuota tiket akan dirilis kembali ke publik.
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 font-extrabold flex items-center justify-center text-sm border border-blue-200">4</span>
                    Larangan Bot & Manipulasi Sistem
                </h2>
                <p>
                    Dilarang keras menggunakan perangkat lunak otomatis, skrip bot, atau trik teknis lainnya untuk memborong tiket pada saat penjualan tiket dibuka (*Ticket War*). Ticketly menerapkan sistem deteksi bot canggih dan berhak membatalkan pesanan sepihak tanpa ganti rugi jika terbukti melakukan pelanggaran.
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 font-extrabold flex items-center justify-center text-sm border border-blue-200">5</span>
                    Kebijakan Pembatalan & Pengembalian Dana (Refund)
                </h2>
                <p>
                    Semua tiket yang telah berhasil dibayar bersifat <strong>non-refundable (tidak dapat dibatalkan atau diuangkan kembali)</strong>, kecuali jika event secara resmi dibatalkan atau ditunda tanpa batas waktu oleh pihak Event Organizer (Promotor).
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 font-extrabold flex items-center justify-center text-sm border border-blue-200">6</span>
                    E-Tiket & Validasi di Pintu Masuk
                </h2>
                <p>
                    E-Tiket resmi berupa kode QR unik akan dikirimkan ke email terdaftar dan tersedia di halaman "Tiket Saya". Pengunjung wajib menunjukkan QR code asli (baik digital di HP atau cetak) untuk dipindai oleh petugas gate di lokasi konser. Satu kode QR hanya berlaku untuk 1 (satu) kali scan masuk.
                </p>
            </section>

        </div>

    </div>
</main>

<?= $this->endSection() ?>
